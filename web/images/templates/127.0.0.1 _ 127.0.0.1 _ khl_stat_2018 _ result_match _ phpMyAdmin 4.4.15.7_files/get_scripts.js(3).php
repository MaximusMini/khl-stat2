// CodeMirror, copyright (c) by Marijn Haverbeke and others
// Distributed under an MIT license: http://codemirror.net/LICENSE

(function(mod) {
  if (typeof exports == "object" && typeof module == "object") // CommonJS
    mod(require("../../lib/codemirror"));
  else if (typeof define == "function" && define.amd) // AMD
    define(["../../lib/codemirror"], mod);
  else // Plain browser env
    mod(CodeMirror);
})(function(CodeMirror) {
  "use strict";

  var HINT_ELEMENT_CLASS        = "CodeMirror-hint";
  var ACTIVE_HINT_ELEMENT_CLASS = "CodeMirror-hint-active";

  // This is the old interface, kept around for now to stay
  // backwards-compatible.
  CodeMirror.showHint = function(cm, getHints, options) {
    if (!getHints) return cm.showHint(options);
    if (options && options.async) getHints.async = true;
    var newOpts = {hint: getHints};
    if (options) for (var prop in options) newOpts[prop] = options[prop];
    return cm.showHint(newOpts);
  };

  var asyncRunID = 0;
  function retrieveHints(getter, cm, options, then) {
    if (getter.async) {
      var id = ++asyncRunID;
      getter(cm, function(hints) {
        if (asyncRunID == id) then(hints);
      }, options);
    } else {
      then(getter(cm, options));
    }
  }

  CodeMirror.defineExtension("showHint", function(options) {
    // We want a single cursor position.
    if (this.listSelections().length > 1 || this.somethingSelected()) return;

    if (this.state.completionActive) this.state.completionActive.close();
    var completion = this.state.completionActive = new Completion(this, options);
    var getHints = completion.options.hint;
    if (!getHints) return;

    CodeMirror.signal(this, "startCompletion", this);
    return retrieveHints(getHints, this, completion.options, function(hints) { completion.showHints(hints); });
  });

  function Completion(cm, options) {
    this.cm = cm;
    this.options = this.buildOptions(options);
    this.widget = this.onClose = null;
  }

  Completion.prototype = {
    close: function() {
      if (!this.active()) return;
      this.cm.state.completionActive = null;

      if (this.widget) this.widget.close();
      if (this.onClose) this.onClose();
      CodeMirror.signal(this.cm, "endCompletion", this.cm);
    },

    active: function() {
      return this.cm.state.completionActive == this;
    },

    pick: function(data, i) {
      var completion = data.list[i];
      if (completion.hint) completion.hint(this.cm, data, completion);
      else this.cm.replaceRange(getText(completion), completion.from || data.from,
                                completion.to || data.to, "complete");
      CodeMirror.signal(data, "pick", completion);
      this.close();
    },

    showHints: function(data) {
      if (!data || !data.list.length || !this.active()) return this.close();

      if (this.options.completeSingle && data.list.length == 1)
        this.pick(data, 0);
      else
        this.showWidget(data);
    },

    showWidget: function(data) {
      this.widget = new Widget(this, data);
      CodeMirror.signal(data, "shown");

      var debounce = 0, completion = this, finished;
      var closeOn = this.options.closeCharacters;
      var startPos = this.cm.getCursor(), startLen = this.cm.getLine(startPos.line).length;

      var requestAnimationFrame = window.requestAnimationFrame || function(fn) {
        return setTimeout(fn, 1000/60);
      };
      var cancelAnimationFrame = window.cancelAnimationFrame || clearTimeout;

      function done() {
        if (finished) return;
        finished = true;
        completion.close();
        completion.cm.off("cursorActivity", activity);
        if (data) CodeMirror.signal(data, "close");
      }

      function update() {
        if (finished) return;
        CodeMirror.signal(data, "update");
        retrieveHints(completion.options.hint, completion.cm, completion.options, finishUpdate);
      }
      function finishUpdate(data_) {
        data = data_;
        if (finished) return;
        if (!data || !data.list.length) return done();
        if (completion.widget) completion.widget.close();
        completion.widget = new Widget(completion, data);
      }

      function clearDebounce() {
        if (debounce) {
          cancelAnimationFrame(debounce);
          debounce = 0;
        }
      }

      function activity() {
        clearDebounce();
        var pos = completion.cm.getCursor(), line = completion.cm.getLine(pos.line);
        if (pos.line != startPos.line || line.length - pos.ch != startLen - startPos.ch ||
            pos.ch < startPos.ch || completion.cm.somethingSelected() ||
            (pos.ch && closeOn.test(line.charAt(pos.ch - 1)))) {
          completion.close();
        } else {
          debounce = requestAnimationFrame(update);
          if (completion.widget) completion.widget.close();
        }
      }
      this.cm.on("cursorActivity", activity);
      this.onClose = done;
    },

    buildOptions: function(options) {
      var editor = this.cm.options.hintOptions;
      var out = {};
      for (var prop in defaultOptions) out[prop] = defaultOptions[prop];
      if (editor) for (var prop in editor)
        if (editor[prop] !== undefined) out[prop] = editor[prop];
      if (options) for (var prop in options)
        if (options[prop] !== undefined) out[prop] = options[prop];
      return out;
    }
  };

  function getText(completion) {
    if (typeof completion == "string") return completion;
    else return completion.text;
  }

  function buildKeyMap(completion, handle) {
    var baseMap = {
      Up: function() {handle.moveFocus(-1);},
      Down: function() {handle.moveFocus(1);},
      PageUp: function() {handle.moveFocus(-handle.menuSize() + 1, true);},
      PageDown: function() {handle.moveFocus(handle.menuSize() - 1, true);},
      Home: function() {handle.setFocus(0);},
      End: function() {handle.setFocus(handle.length - 1);},
      Enter: handle.pick,
      Tab: handle.pick,
      Esc: handle.close
    };
    var custom = completion.options.customKeys;
    var ourMap = custom ? {} : baseMap;
    function addBinding(key, val) {
      var bound;
      if (typeof val != "string")
        bound = function(cm) { return val(cm, handle); };
      // This mechanism is deprecated
      else if (baseMap.hasOwnProperty(val))
        bound = baseMap[val];
      else
        bound = val;
      ourMap[key] = bound;
    }
    if (custom)
      for (var key in custom) if (custom.hasOwnProperty(key))
        addBinding(key, custom[key]);
    var extra = completion.options.extraKeys;
    if (extra)
      for (var key in extra) if (extra.hasOwnProperty(key))
        addBinding(key, extra[key]);
    return ourMap;
  }

  function getHintElement(hintsElement, el) {
    while (el && el != hintsElement) {
      if (el.nodeName.toUpperCase() === "LI" && el.parentNode == hintsElement) return el;
      el = el.parentNode;
    }
  }

  function Widget(completion, data) {
    this.completion = completion;
    this.data = data;
    var widget = this, cm = completion.cm;

    var hints = this.hints = document.createElement("ul");
    hints.className = "CodeMirror-hints";
    this.selectedHint = data.selectedHint || 0;

    var completions = data.list;
    for (var i = 0; i < completions.length; ++i) {
      var elt = hints.appendChild(document.createElement("li")), cur = completions[i];
      var className = HINT_ELEMENT_CLASS + (i != this.selectedHint ? "" : " " + ACTIVE_HINT_ELEMENT_CLASS);
      if (cur.className != null) className = cur.className + " " + className;
      elt.className = className;
      if (cur.render) cur.render(elt, data, cur);
      else elt.appendChild(document.createTextNode(cur.displayText || getText(cur)));
      elt.hintId = i;
    }

    var pos = cm.cursorCoords(completion.options.alignWithWord ? data.from : null);
    var left = pos.left, top = pos.bottom, below = true;
    hints.style.left = left + "px";
    hints.style.top = top + "px";
    // If we're at the edge of the screen, then we want the menu to appear on the left of the cursor.
    var winW = window.innerWidth || Math.max(document.body.offsetWidth, document.documentElement.offsetWidth);
    var winH = window.innerHeight || Math.max(document.body.offsetHeight, document.documentElement.offsetHeight);
    (completion.options.container || document.body).appendChild(hints);
    var box = hints.getBoundingClientRect(), overlapY = box.bottom - winH;
    if (overlapY > 0) {
      var height = box.bottom - box.top, curTop = pos.top - (pos.bottom - box.top);
      if (curTop - height > 0) { // Fits above cursor
        hints.style.top = (top = pos.top - height) + "px";
        below = false;
      } else if (height > winH) {
        hints.style.height = (winH - 5) + "px";
        hints.style.top = (top = pos.bottom - box.top) + "px";
        var cursor = cm.getCursor();
        if (data.from.ch != cursor.ch) {
          pos = cm.cursorCoords(cursor);
          hints.style.left = (left = pos.left) + "px";
          box = hints.getBoundingClientRect();
        }
      }
    }
    var overlapX = box.right - winW;
    if (overlapX > 0) {
      if (box.right - box.left > winW) {
        hints.style.width = (winW - 5) + "px";
        overlapX -= (box.right - box.left) - winW;
      }
      hints.style.left = (left = pos.left - overlapX) + "px";
    }

    cm.addKeyMap(this.keyMap = buildKeyMap(completion, {
      moveFocus: function(n, avoidWrap) { widget.changeActive(widget.selectedHint + n, avoidWrap); },
      setFocus: function(n) { widget.changeActive(n); },
      menuSize: function() { return widget.screenAmount(); },
      length: completions.length,
      close: function() { completion.close(); },
      pick: function() { widget.pick(); },
      data: data
    }));

    if (completion.options.closeOnUnfocus) {
      var closingOnBlur;
      cm.on("blur", this.onBlur = function() { closingOnBlur = setTimeout(function() { completion.close(); }, 100); });
      cm.on("focus", this.onFocus = function() { clearTimeout(closingOnBlur); });
    }

    var startScroll = cm.getScrollInfo();
    cm.on("scroll", this.onScroll = function() {
      var curScroll = cm.getScrollInfo(), editor = cm.getWrapperElement().getBoundingClientRect();
      var newTop = top + startScroll.top - curScroll.top;
      var point = newTop - (window.pageYOffset || (document.documentElement || document.body).scrollTop);
      if (!below) point += hints.offsetHeight;
      if (point <= editor.top || point >= editor.bottom) return completion.close();
      hints.style.top = newTop + "px";
      hints.style.left = (left + startScroll.left - curScroll.left) + "px";
    });

    CodeMirror.on(hints, "dblclick", function(e) {
      var t = getHintElement(hints, e.target || e.srcElement);
      if (t && t.hintId != null) {widget.changeActive(t.hintId); widget.pick();}
    });

    CodeMirror.on(hints, "click", function(e) {
      var t = getHintElement(hints, e.target || e.srcElement);
      if (t && t.hintId != null) {
        widget.changeActive(t.hintId);
        if (completion.options.completeOnSingleClick) widget.pick();
      }
    });

    CodeMirror.on(hints, "mousedown", function() {
      setTimeout(function(){cm.focus();}, 20);
    });

    CodeMirror.signal(data, "select", completions[0], hints.firstChild);
    return true;
  }

  Widget.prototype = {
    close: function() {
      if (this.completion.widget != this) return;
      this.completion.widget = null;
      this.hints.parentNode.removeChild(this.hints);
      this.completion.cm.removeKeyMap(this.keyMap);

      var cm = this.completion.cm;
      if (this.completion.options.closeOnUnfocus) {
        cm.off("blur", this.onBlur);
        cm.off("focus", this.onFocus);
      }
      cm.off("scroll", this.onScroll);
    },

    pick: function() {
      this.completion.pick(this.data, this.selectedHint);
    },

    changeActive: function(i, avoidWrap) {
      if (i >= this.data.list.length)
        i = avoidWrap ? this.data.list.length - 1 : 0;
      else if (i < 0)
        i = avoidWrap ? 0  : this.data.list.length - 1;
      if (this.selectedHint == i) return;
      var node = this.hints.childNodes[this.selectedHint];
      node.className = node.className.replace(" " + ACTIVE_HINT_ELEMENT_CLASS, "");
      node = this.hints.childNodes[this.selectedHint = i];
      node.className += " " + ACTIVE_HINT_ELEMENT_CLASS;
      if (node.offsetTop < this.hints.scrollTop)
        this.hints.scrollTop = node.offsetTop - 3;
      else if (node.offsetTop + node.offsetHeight > this.hints.scrollTop + this.hints.clientHeight)
        this.hints.scrollTop = node.offsetTop + node.offsetHeight - this.hints.clientHeight + 3;
      CodeMirror.signal(this.data, "select", this.data.list[this.selectedHint], node);
    },

    screenAmount: function() {
      return Math.floor(this.hints.clientHeight / this.hints.firstChild.offsetHeight) || 1;
    }
  };

  CodeMirror.registerHelper("hint", "auto", function(cm, options) {
    var helpers = cm.getHelpers(cm.getCursor(), "hint"), words;
    if (helpers.length) {
      for (var i = 0; i < helpers.length; i++) {
        var cur = helpers[i](cm, options);
        if (cur && cur.list.length) return cur;
      }
    } else if (words = cm.getHelper(cm.getCursor(), "hintWords")) {
      if (words) return CodeMirror.hint.fromList(cm, {words: words});
    } else if (CodeMirror.hint.anyword) {
      return CodeMirror.hint.anyword(cm, options);
    }
  });

  CodeMirror.registerHelper("hint", "fromList", function(cm, options) {
    var cur = cm.getCursor(), token = cm.getTokenAt(cur);
    var found = [];
    for (var i = 0; i < options.words.length; i++) {
      var word = options.words[i];
      if (word.slice(0, token.string.length) == token.string)
        found.push(word);
    }

    if (found.length) return {
      list: found,
      from: CodeMirror.Pos(cur.line, token.start),
            to: CodeMirror.Pos(cur.line, token.end)
    };
  });

  CodeMirror.commands.autocomplete = CodeMirror.showHint;

  var defaultOptions = {
    hint: CodeMirror.hint.auto,
    completeSingle: true,
    alignWithWord: true,
    closeCharacters: /[\s()\[\]{};:>,]/,
    closeOnUnfocus: true,
    completeOnSingleClick: false,
    container: null,
    customKeys: null,
    extraKeys: null
  };

  CodeMirror.defineOption("hintOptions", null);
});
;

// CodeMirror, copyright (c) by Marijn Haverbeke and others
// Distributed under an MIT license: http://codemirror.net/LICENSE

(function(mod) {
  if (typeof exports == "object" && typeof module == "object") // CommonJS
    mod(require("../../lib/codemirror"), require("../../mode/sql/sql"));
  else if (typeof define == "function" && define.amd) // AMD
    define(["../../lib/codemirror", "../../mode/sql/sql"], mod);
  else // Plain browser env
    mod(CodeMirror);
})(function(CodeMirror) {
  "use strict";

  var tables;
  var defaultTable;
  var keywords;
  var CONS = {
    QUERY_DIV: ";",
    ALIAS_KEYWORD: "AS"
  };
  var Pos = CodeMirror.Pos;

  function getKeywords(editor) {
    var mode = editor.doc.modeOption;
    if (mode === "sql") mode = "text/x-sql";
    return CodeMirror.resolveMode(mode).keywords;
  }

  function getText(item) {
    return typeof item == "string" ? item : item.text;
  }

  function getItem(list, item) {
    if (!list.slice) return list[item];
    for (var i = list.length - 1; i >= 0; i--) if (getText(list[i]) == item)
      return list[i];
  }

  function shallowClone(object) {
    var result = {};
    for (var key in object) if (object.hasOwnProperty(key))
      result[key] = object[key];
    return result;
  }

  function match(string, word) {
    var len = string.length;
    var sub = getText(word).substr(0, len);
    return string.toUpperCase() === sub.toUpperCase();
  }

  function addMatches(result, search, wordlist, formatter) {
    for (var word in wordlist) {
      if (!wordlist.hasOwnProperty(word)) continue;
      if (Array.isArray(wordlist)) {
        word = wordlist[word];
      }
      if (match(search, word)) {
        result.push(formatter(word));
      }
    }
  }

  function cleanName(name) {
    // Get rid name from backticks(`) and preceding dot(.)
    if (name.charAt(0) == ".") {
      name = name.substr(1);
    }
    return name.replace(/`/g, "");
  }

  function insertBackticks(name) {
    var nameParts = getText(name).split(".");
    for (var i = 0; i < nameParts.length; i++)
      nameParts[i] = "`" + nameParts[i] + "`";
    var escaped = nameParts.join(".");
    if (typeof name == "string") return escaped;
    name = shallowClone(name);
    name.text = escaped;
    return name;
  }

  function nameCompletion(cur, token, result, editor) {
    // Try to complete table, colunm names and return start position of completion
    var useBacktick = false;
    var nameParts = [];
    var start = token.start;
    var cont = true;
    while (cont) {
      cont = (token.string.charAt(0) == ".");
      useBacktick = useBacktick || (token.string.charAt(0) == "`");

      start = token.start;
      nameParts.unshift(cleanName(token.string));

      token = editor.getTokenAt(Pos(cur.line, token.start));
      if (token.string == ".") {
        cont = true;
        token = editor.getTokenAt(Pos(cur.line, token.start));
      }
    }

    // Try to complete table names
    var string = nameParts.join(".");
    addMatches(result, string, tables, function(w) {
      return useBacktick ? insertBackticks(w) : w;
    });

    // Try to complete columns from defaultTable
    addMatches(result, string, defaultTable, function(w) {
      return useBacktick ? insertBackticks(w) : w;
    });

    // Try to complete columns
    string = nameParts.pop();
    var table = nameParts.join(".");

    // Check if table is available. If not, find table by Alias
    if (!getItem(tables, table))
      table = findTableByAlias(table, editor);

    var columns = getItem(tables, table);
    if (columns && Array.isArray(tables) && columns.columns)
      columns = columns.columns;

    if (columns) {
      addMatches(result, string, columns, function(w) {
        if (typeof w == "string") {
          w = table + "." + w;
        } else {
          w = shallowClone(w);
          w.text = table + "." + w.text;
        }
        return useBacktick ? insertBackticks(w) : w;
      });
    }

    return start;
  }

  function eachWord(lineText, f) {
    if (!lineText) return;
    var excepted = /[,;]/g;
    var words = lineText.split(" ");
    for (var i = 0; i < words.length; i++) {
      f(words[i]?words[i].replace(excepted, '') : '');
    }
  }

  function convertCurToNumber(cur) {
    // max characters of a line is 999,999.
    return cur.line + cur.ch / Math.pow(10, 6);
  }

  function convertNumberToCur(num) {
    return Pos(Math.floor(num), +num.toString().split('.').pop());
  }

  function findTableByAlias(alias, editor) {
    var doc = editor.doc;
    var fullQuery = doc.getValue();
    var aliasUpperCase = alias.toUpperCase();
    var previousWord = "";
    var table = "";
    var separator = [];
    var validRange = {
      start: Pos(0, 0),
      end: Pos(editor.lastLine(), editor.getLineHandle(editor.lastLine()).length)
    };

    //add separator
    var indexOfSeparator = fullQuery.indexOf(CONS.QUERY_DIV);
    while(indexOfSeparator != -1) {
      separator.push(doc.posFromIndex(indexOfSeparator));
      indexOfSeparator = fullQuery.indexOf(CONS.QUERY_DIV, indexOfSeparator+1);
    }
    separator.unshift(Pos(0, 0));
    separator.push(Pos(editor.lastLine(), editor.getLineHandle(editor.lastLine()).text.length));

    //find valid range
    var prevItem = 0;
    var current = convertCurToNumber(editor.getCursor());
    for (var i=0; i< separator.length; i++) {
      var _v = convertCurToNumber(separator[i]);
      if (current > prevItem && current <= _v) {
        validRange = { start: convertNumberToCur(prevItem), end: convertNumberToCur(_v) };
        break;
      }
      prevItem = _v;
    }

    var query = doc.getRange(validRange.start, validRange.end, false);

    for (var i = 0; i < query.length; i++) {
      var lineText = query[i];
      eachWord(lineText, function(word) {
        var wordUpperCase = word.toUpperCase();
        if (wordUpperCase === aliasUpperCase && getItem(tables, previousWord))
          table = previousWord;
        if (wordUpperCase !== CONS.ALIAS_KEYWORD)
          previousWord = word;
      });
      if (table) break;
    }
    return table;
  }

  CodeMirror.registerHelper("hint", "sql", function(editor, options) {
    tables = (options && options.tables) || {};
    var defaultTableName = options && options.defaultTable;
    defaultTable = defaultTableName && getItem(tables, defaultTableName);
    keywords = keywords || getKeywords(editor);

    if (defaultTableName && !defaultTable)
      defaultTable = findTableByAlias(defaultTableName, editor);

    defaultTable = defaultTable || [];

    if (Array.isArray(tables) && defaultTable.columns)
      defaultTable = defaultTable.columns;

    var cur = editor.getCursor();
    var result = [];
    var token = editor.getTokenAt(cur), start, end, search;
    if (token.end > cur.ch) {
      token.end = cur.ch;
      token.string = token.string.slice(0, cur.ch - token.start);
    }

    if (token.string.match(/^[.`\w@]\w*$/)) {
      search = token.string;
      start = token.start;
      end = token.end;
    } else {
      start = end = cur.ch;
      search = "";
    }
    if (search.charAt(0) == "." || search.charAt(0) == "`") {
      start = nameCompletion(cur, token, result, editor);
    } else {
      addMatches(result, search, tables, function(w) {return w;});
      addMatches(result, search, defaultTable, function(w) {return w;});
      addMatches(result, search, keywords, function(w) {return w.toUpperCase();});
    }

    return {list: result, from: Pos(cur.line, start), to: Pos(cur.line, end)};
  });
});
;

/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Used in or for console
 *
 * @package phpMyAdmin-Console
 */

/**
 * Console object
 */
var PMA_console = {
    /**
     * @var object, jQuery object, selector is '#pma_console>.content'
     * @access private
     */
    $consoleContent: null,
    /**
     * @var object, jQuery object, selector is '#pma_console .content',
     *  used for resizer
     * @access private
     */
    $consoleAllContents: null,
    /**
     * @var object, jQuery object, selector is '#pma_console .toolbar'
     * @access private
     */
    $consoleToolbar: null,
    /**
     * @var object, jQuery object, selector is '#pma_console .template'
     * @access private
     */
    $consoleTemplates: null,
    /**
     * @var object, jQuery object, form for submit
     * @access private
     */
    $requestForm: null,
    /**
     * @var object, contain console config
     * @access private
     */
    config: null,
    /**
     * @var bool, if console element exist, it'll be true
     * @access public
     */
    isEnabled: false,
    /**
     * @var bool, make sure console events bind only once
     * @access private
     */
    isInitialized: false,
    /**
     * Used for console initialize, reinit is ok, just some variable assignment
     *
     * @return void
     */
    initialize: function() {

        if($('#pma_console').length === 0) {
            return;
        }

        PMA_console.isEnabled = true;

        // Cookie var checks and init
        if(! $.cookie('pma_console_height')) {
            $.cookie('pma_console_height', 92);
        }
        if(! $.cookie('pma_console_mode')) {
            $.cookie('pma_console_mode', 'info');
        }

        // Vars init
        PMA_console.$consoleToolbar = $('#pma_console>.toolbar');
        PMA_console.$consoleContent = $('#pma_console>.content');
        PMA_console.$consoleAllContents = $('#pma_console .content');
        PMA_console.$consoleTemplates = $('#pma_console>.templates');

        // Generate a from for post
        PMA_console.$requestForm = $('<form method="post" action="import.php">' +
            '<input name="is_js_confirmed" value="0">' +
            '<textarea name="sql_query"></textarea>' +
            '<input name="console_message_id" value="0">' +
            '<input name="server" value="">' +
            '<input name="db" value="">' +
            '<input name="table" value="">' +
            '<input name="token" value="' +
            PMA_commonParams.get('token') +
            '">' +
            '</form>'
        );
        PMA_console.$requestForm.bind('submit', AJAX.requestHandler);

        // Event binds shouldn't run again
        if(PMA_console.isInitialized === false) {

            // Load config first
            var tempConfig = JSON.parse($.cookie('pma_console_config'));
            if(tempConfig) {
                if(tempConfig.alwaysExpand === true) {
                    $('#pma_console_options input[name=always_expand]').prop('checked', true);
                }
                if(tempConfig.startHistory === true) {
                    $('#pma_console_options input[name=start_history]').prop('checked', true);
                }
                if(tempConfig.currentQuery === true) {
                    $('#pma_console_options input[name=current_query]').prop('checked', true);
                }
            } else {
                $('#pma_console_options input[name=current_query]').prop('checked', true);
            }

            PMA_console.updateConfig();

            PMA_consoleResizer.initialize();
            PMA_consoleInput.initialize();
            PMA_consoleMessages.initialize();
            PMA_consoleBookmarks.initialize();

            PMA_console.$consoleToolbar.children('.console_switch').click(PMA_console.toggle);
            $(document).keydown(function(event) {
                // Ctrl + Alt + C
                if(event.ctrlKey && event.altKey && event.keyCode === 67) {
                    PMA_console.toggle();
                }
            });

            $('#pma_console .toolbar').children().mousedown(function(event) {
                event.preventDefault();
                event.stopImmediatePropagation();
            });

            $('#pma_console .button.clear').click(function() {
                PMA_consoleMessages.clear();
            });

            $('#pma_console .button.history').click(function() {
                PMA_consoleMessages.showHistory();
            });

            $('#pma_console .button.options').click(function() {
                PMA_console.showCard('#pma_console_options');
            });

            PMA_console.$consoleContent.click(function(event) {
                if (event.target == this) {
                    PMA_consoleInput.focus();
                }
            });

            $('#pma_console .mid_layer').click(function() {
                PMA_console.hideCard($(this).parent().children('.card'));
            });
            $('#pma_bookmarks .switch_button').click(function() {
                PMA_console.hideCard($(this).closest('.card'));
            });

            $('#pma_console_options input[type=checkbox]').change(function() {
                PMA_console.updateConfig();
            });

            $('#pma_console_options .button.default').click(function() {
                $('#pma_console_options input[name=always_expand]').prop('checked', false);
                $('#pma_console_options input[name=start_history]').prop('checked', false);
                $('#pma_console_options input[name=current_query]').prop('checked', true);
                PMA_console.updateConfig();
            });

            $(document).ajaxComplete(function (event, xhr) {
                try {
                    var data = $.parseJSON(xhr.responseText);
                    PMA_console.ajaxCallback(data);
                } catch (e) {
                    console.log("Invalid JSON!" + e.message);
                    if(AJAX.xhr && AJAX.xhr.status === 0 && AJAX.xhr.statusText !== 'abort') {
                        PMA_ajaxShowMessage($('<div />',{class:'error',html:PMA_messages.strRequestFailed+' ( '+escapeHtml(AJAX.xhr.statusText)+' )'}));
                        AJAX.active = false;
                        AJAX.xhr = null;
                    }
                }
            });

            PMA_console.isInitialized = true;
        }

        // Change console mode from cookie
        switch($.cookie('pma_console_mode')) {
            case 'collapse':
                PMA_console.collapse();
                break;
            /* jshint -W086 */// no break needed in default section
            default:
                $.cookie('pma_console_mode', 'info');
            case 'info':
            /* jshint +W086 */
                PMA_console.info();
                break;
            case 'show':
                PMA_console.show(true);
                PMA_console.scrollBottom();
                break;
        }
    },
    /**
     * Execute query and show results in console
     *
     * @return void
     */
    execute: function(queryString, options) {
        if(typeof(queryString) != 'string' || ! /[a-z]|[A-Z]/.test(queryString)){
            return;
        }
        PMA_console.$requestForm.children('textarea').val(queryString);
        PMA_console.$requestForm.children('[name=server]').attr('value', PMA_commonParams.get('server'));
        if(options && options.db) {
            PMA_console.$requestForm.children('[name=db]').val(options.db);
            if(options.table) {
                PMA_console.$requestForm.children('[name=table]').val(options.table);
            } else {
                PMA_console.$requestForm.children('[name=table]').val('');
            }
        } else {
            PMA_console.$requestForm.children('[name=db]').val(
                (PMA_commonParams.get('db').length > 0 ? PMA_commonParams.get('db') : ''));
        }
        PMA_console.$requestForm.find('[name=profiling]').remove();
        if(options && options.profiling === true) {
            PMA_console.$requestForm.append('<input name="profiling" value="on">');
        }
        if (! confirmQuery(PMA_console.$requestForm[0], PMA_console.$requestForm.children('textarea')[0])) {
            return;
        }
        PMA_console.$requestForm.children('[name=console_message_id]')
            .val(PMA_consoleMessages.appendQuery({sql_query: queryString}).message_id);
        PMA_console.$requestForm.trigger('submit');
        PMA_consoleInput.clear();
        PMA_reloadNavigation();
    },
    ajaxCallback: function(data) {
        if(data && data.console_message_id) {
            PMA_consoleMessages.updateQuery(data.console_message_id, data.success,
                (data._reloadQuerywindow ? data._reloadQuerywindow : false));
        } else if( data && data._reloadQuerywindow) {
            if(data._reloadQuerywindow.sql_query.length > 0) {
                PMA_consoleMessages.appendQuery(data._reloadQuerywindow, 'successed')
                    .$message.addClass(PMA_console.config.currentQuery ? '' : 'hide');
            }
        }
    },
    /**
     * Change console to collapse mode
     *
     * @return void
     */
    collapse: function() {
        $.cookie('pma_console_mode', 'collapse');
        var pmaConsoleHeight = $.cookie('pma_console_height');

        if(pmaConsoleHeight < 32) {
            $.cookie('pma_console_height', 92);
        }
        PMA_console.$consoleToolbar.addClass('collapsed');
        PMA_console.$consoleAllContents.height(pmaConsoleHeight);
        PMA_console.$consoleContent.stop();
        PMA_console.$consoleContent.animate({'margin-bottom': -1 * PMA_console.$consoleContent.outerHeight() + 'px'},
            'fast', 'easeOutQuart', function() {
                PMA_console.$consoleContent.css({display:'none'});
                $(window).trigger('resize');
            });
        PMA_console.hideCard();
    },
    /**
     * Show console
     *
     * @param bool inputFocus If true, focus the input line after show()
     * @return void
     */
    show: function(inputFocus) {
        $.cookie('pma_console_mode', 'show');

        var pmaConsoleHeight = $.cookie('pma_console_height');

        if(pmaConsoleHeight < 32) {
            $.cookie('pma_console_height', 32);
            PMA_console.collapse();
            return;
        }
        PMA_console.$consoleContent.css({display:'block'});
        if(PMA_console.$consoleToolbar.hasClass('collapsed')) {
            PMA_console.$consoleToolbar.removeClass('collapsed');
        }
        PMA_console.$consoleAllContents.height(pmaConsoleHeight);
        PMA_console.$consoleContent.stop();
        PMA_console.$consoleContent.animate({'margin-bottom': 0},
            'fast', 'easeOutQuart', function() {
                $(window).trigger('resize');
                if(inputFocus) {
                    PMA_consoleInput.focus();
                }
            });
    },
    /**
     * Change console to SQL information mode
     * this mode shows current SQL query
     * This mode is the default mode
     *
     * @return void
     */
    info: function() {
        // Under construction
        PMA_console.collapse();
    },
    /**
     * Toggle console mode between collapse/show
     * Used for toggle buttons and shortcuts
     *
     * @return void
     */
    toggle: function() {
        switch($.cookie('pma_console_mode')) {
            case 'collapse':
            case 'info':
                PMA_console.show(true);
                break;
            case 'show':
                PMA_console.collapse();
                break;
            default:
                PMA_consoleInitialize();
        }
    },
    /**
     * Scroll console to bottom
     *
     * @return void
     */
    scrollBottom: function() {
        PMA_console.$consoleContent.scrollTop(PMA_console.$consoleContent.prop("scrollHeight"));
    },
    /**
     * Show card
     *
     * @param string cardSelector Selector, select string will be "#pma_console " + cardSelector
     * this param also can be JQuery object, if you need.
     *
     * @return void
     */
    showCard: function(cardSelector) {
        var $card = null;
        if(typeof(cardSelector) !== 'string') {
            if (cardSelector.length > 0) {
                $card = cardSelector;
            } else {
                return;
            }
        } else {
            $card = $("#pma_console " + cardSelector);
        }
        if($card.length === 0) {
            return;
        }
        $card.parent().children('.mid_layer').show().fadeTo(0, 0.15);
        $card.addClass('show');
        PMA_consoleInput.blur();
        if($card.parents('.card').length > 0) {
            PMA_console.showCard($card.parents('.card'));
        }
    },
    /**
     * Scroll console to bottom
     *
     * @param object $targetCard Target card JQuery object, if it's empty, function will hide all cards
     * @return void
     */
    hideCard: function($targetCard) {
        if(! $targetCard) {
            $('#pma_console .mid_layer').fadeOut(140);
            $('#pma_console .card').removeClass('show');
        } else if($targetCard.length > 0) {
            $targetCard.parent().find('.mid_layer').fadeOut(140);
            $targetCard.find('.card').removeClass('show');
            $targetCard.removeClass('show');
        }
    },
    /**
     * Used for update console config
     *
     * @return void
     */
    updateConfig: function() {
        PMA_console.config = {
            alwaysExpand: $('#pma_console_options input[name=always_expand]').prop('checked'),
            startHistory: $('#pma_console_options input[name=start_history]').prop('checked'),
            currentQuery: $('#pma_console_options input[name=current_query]').prop('checked')
        };
        $.cookie('pma_console_config', JSON.stringify(PMA_console.config));
    },
    isSelect: function (queryString) {
        var reg_exp = /^SELECT\s+/i;
        return reg_exp.test(queryString);
    }
};

/**
 * Resizer object
 * Careful: this object UI logics highly related with functions under PMA_console
 * Resizing min-height is 32, if small than it, console will collapse
 */
var PMA_consoleResizer = {
    _posY: 0,
    _height: 0,
    _resultHeight: 0,
    /**
     * Mousedown event handler for bind to resizer
     *
     * @return void
     */
    _mousedown: function(event) {
        if($.cookie('pma_console_mode') !== 'show') {
            return;
        }
        PMA_consoleResizer._posY = event.pageY;
        PMA_consoleResizer._height = PMA_console.$consoleContent.height();
        $(document).mousemove(PMA_consoleResizer._mousemove);
        $(document).mouseup(PMA_consoleResizer._mouseup);
        // Disable text selection while resizing
        $(document).bind('selectstart', function(){ return false; });
    },
    /**
     * Mousemove event handler for bind to resizer
     *
     * @return void
     */
    _mousemove: function(event) {
        if (event.pageY < 35) {
            event.pageY = 35
        }
        PMA_consoleResizer._resultHeight = PMA_consoleResizer._height + (PMA_consoleResizer._posY -event.pageY);
        // Content min-height is 32, if adjusting height small than it we'll move it out of the page
        if(PMA_consoleResizer._resultHeight <= 32) {
            PMA_console.$consoleAllContents.height(32);
            PMA_console.$consoleContent.css('margin-bottom', PMA_consoleResizer._resultHeight - 32);
        }
        else {
            // Logic below makes viewable area always at bottom when adjusting height and content already at bottom
            if(PMA_console.$consoleContent.scrollTop() + PMA_console.$consoleContent.innerHeight() + 16
                >= PMA_console.$consoleContent.prop('scrollHeight')) {
                PMA_console.$consoleAllContents.height(PMA_consoleResizer._resultHeight);
                PMA_console.scrollBottom();
            } else {
                PMA_console.$consoleAllContents.height(PMA_consoleResizer._resultHeight);
            }
        }
    },
    /**
     * Mouseup event handler for bind to resizer
     *
     * @return void
     */
    _mouseup: function() {
        $.cookie('pma_console_height', PMA_consoleResizer._resultHeight);
        PMA_console.show();
        $(document).unbind('mousemove');
        $(document).unbind('mouseup');
        $(document).unbind('selectstart');
    },
    /**
     * Used for console resizer initialize
     *
     * @return void
     */
    initialize: function() {
        $('#pma_console .toolbar').unbind('mousedown');
        $('#pma_console .toolbar').mousedown(PMA_consoleResizer._mousedown);
    }
};


/**
 * Console input object
 */
var PMA_consoleInput = {
    /**
     * @var array, contains Codemirror objects or input jQuery objects
     * @access private
     */
    _inputs: null,
    /**
     * @var bool, if codemirror enabled
     * @access private
     */
    _codemirror: false,
    /**
     * @var int, count for history navigation, 0 for current input
     * @access private
     */
    _historyCount: 0,
    /**
     * @var string, current input when navigating through history
     * @access private
     */
    _historyPreserveCurrent: null,
    /**
     * Used for console input initialize
     *
     * @return void
     */
    initialize: function() {
        // _cm object can't be reinitialize
        if(PMA_consoleInput._inputs !== null) {
            return;
        }
        if(typeof CodeMirror !== 'undefined') {
            PMA_consoleInput._codemirror = true;
        }
        PMA_consoleInput._inputs = [];
        if (PMA_consoleInput._codemirror) {
            PMA_consoleInput._inputs.console = CodeMirror($('#pma_console .console_query_input')[0], {
                theme: 'pma',
                mode: 'text/x-sql',
                lineWrapping: true,
                extraKeys: {"Ctrl-Space": "autocomplete"},
                hintOptions: {"completeSingle": false, "completeOnSingleClick": true}
            });
            PMA_consoleInput._inputs.console.on("inputRead", codemirrorAutocompleteOnInputRead);
            PMA_consoleInput._inputs.console.on("keydown", function(instance, event) {
                PMA_consoleInput._historyNavigate(event);
            });
            if ($('#pma_bookmarks').length !== 0) {
                PMA_consoleInput._inputs.bookmark = CodeMirror($('#pma_console .bookmark_add_input')[0], {
                    theme: 'pma',
                    mode: 'text/x-sql',
                    lineWrapping: true,
                    extraKeys: {"Ctrl-Space": "autocomplete"},
                    hintOptions: {"completeSingle": false, "completeOnSingleClick": true}
                });
                PMA_consoleInput._inputs.bookmark.on("inputRead", codemirrorAutocompleteOnInputRead);
            }
        } else {
            PMA_consoleInput._inputs.console =
                $('<textarea>').appendTo('#pma_console .console_query_input')
                    .on('keydown', PMA_consoleInput._historyNavigate);
            if ($('#pma_bookmarks').length !== 0) {
                PMA_consoleInput._inputs.bookmark =
                    $('<textarea>').appendTo('#pma_console .bookmark_add_input');
            }
        }
        $('#pma_console .console_query_input').keydown(PMA_consoleInput._keydown);
    },
    _historyNavigate: function(event) {
        if (event.keyCode == 38 || event.keyCode == 40) {
            var upPermitted = false;
            var downPermitted = false;
            var editor = PMA_consoleInput._inputs.console;
            var cursorLine;
            var totalLine;
            if (PMA_consoleInput._codemirror) {
                cursorLine = editor.getCursor().line;
                totalLine = editor.lineCount();
            } else {
                // Get cursor position from textarea
                var text = PMA_consoleInput.getText();
                cursorLine = text.substr(0, editor.prop("selectionStart")).split("\n").length - 1;
                totalLine = text.split(/\r*\n/).length;
            }
            if (cursorLine === 0) {
                upPermitted = true;
            }
            if (cursorLine == totalLine - 1) {
                downPermitted = true;
            }
            var nextCount;
            var queryString = false;
            if (upPermitted && event.keyCode == 38) {
                // Navigate up in history
                if (PMA_consoleInput._historyCount === 0) {
                    PMA_consoleInput._historyPreserveCurrent = PMA_consoleInput.getText();
                }
                nextCount = PMA_consoleInput._historyCount + 1;
                queryString = PMA_consoleMessages.getHistory(nextCount);
            } else if (downPermitted && event.keyCode == 40) {
                // Navigate down in history
                if (PMA_consoleInput._historyCount === 0) {
                    return;
                }
                nextCount = PMA_consoleInput._historyCount - 1;
                if (nextCount === 0) {
                    queryString = PMA_consoleInput._historyPreserveCurrent;
                } else {
                    queryString = PMA_consoleMessages.getHistory(nextCount);
                }
            }
            if (queryString !== false) {
                PMA_consoleInput._historyCount = nextCount;
                PMA_consoleInput.setText(queryString, 'console');
                if (PMA_consoleInput._codemirror) {
                    editor.setCursor(editor.lineCount(), 0);
                }
                event.preventDefault();
            }
        }
    },
    /**
     * Mousedown event handler for bind to input
     * Shortcut is Ctrl+Enter key
     *
     * @return void
     */
    _keydown: function(event) {
        if(event.ctrlKey && event.keyCode === 13) {
            PMA_consoleInput.execute();
        }
    },
    /**
     * Used for send text to PMA_console.execute()
     *
     * @return void
     */
    execute: function() {
        if (PMA_consoleInput._codemirror) {
            PMA_console.execute(PMA_consoleInput._inputs.console.getValue());
        } else {
            PMA_console.execute(PMA_consoleInput._inputs.console.val());
        }
    },
    /**
     * Used for clear the input
     *
     * @param string target, default target is console input
     * @return void
     */
    clear: function(target) {
        PMA_consoleInput.setText('', target);
    },
    /**
     * Used for set focus to input
     *
     * @return void
     */
    focus: function() {
        PMA_consoleInput._inputs.console.focus();
    },
    /**
     * Used for blur input
     *
     * @return void
     */
    blur: function() {
        if (PMA_consoleInput._codemirror) {
            PMA_consoleInput._inputs.console.getInputField().blur();
        } else {
            PMA_consoleInput._inputs.console.blur();
        }
    },
    /**
     * Used for set text in input
     *
     * @param string text
     * @param string target
     * @return void
     */
    setText: function(text, target) {
        if (PMA_consoleInput._codemirror) {
            switch(target) {
                case 'bookmark':
                    PMA_console.execute(PMA_consoleInput._inputs.bookmark.setValue(text));
                    break;
                default:
                case 'console':
                    PMA_console.execute(PMA_consoleInput._inputs.console.setValue(text));
            }
        } else {
            switch(target) {
                case 'bookmark':
                    PMA_console.execute(PMA_consoleInput._inputs.bookmark.val(text));
                    break;
                default:
                case 'console':
                    PMA_console.execute(PMA_consoleInput._inputs.console.val(text));
            }
        }
    },
    getText: function(target) {
        if (PMA_consoleInput._codemirror) {
            switch(target) {
                case 'bookmark':
                    return PMA_consoleInput._inputs.bookmark.getValue();
                default:
                case 'console':
                    return PMA_consoleInput._inputs.console.getValue();
            }
        } else {
            switch(target) {
                case 'bookmark':
                    return PMA_consoleInput._inputs.bookmark.val();
                default:
                case 'console':
                    return PMA_consoleInput._inputs.console.val();
            }
        }
    }

};


/**
 * Console messages, and message items management object
 */
var PMA_consoleMessages = {
    /**
     * Used for clear the messages
     *
     * @return void
     */
    clear: function() {
        $('#pma_console .content .console_message_container .message:not(.welcome)').addClass('hide');
        $('#pma_console .content .console_message_container .message.failed').remove();
        $('#pma_console .content .console_message_container .message.expanded').find('.action.collapse').click();
    },
    /**
     * Used for show history messages
     *
     * @return void
     */
    showHistory: function() {
        $('#pma_console .content .console_message_container .message.hide').removeClass('hide');
    },
    /**
     * Used for getting a perticular history query
     *
     * @param int nthLast get nth query message from latest, i.e 1st is last
     * @return string message
     */
    getHistory: function(nthLast) {
        var $queries = $('#pma_console .content .console_message_container .query');
        var length = $queries.length;
        var $query = $queries.eq(length - nthLast);
        if (!$query || (length - nthLast) < 0) {
            return false;
        } else {
            return $query.text();
        }
    },
    /**
     * Used for log new message
     *
     * @param string msgString Message to show
     * @param string msgType Message type
     * @return object, {message_id, $message}
     */
    append: function(msgString, msgType) {
        if(typeof(msgString) !== 'string') {
            return false;
        }
        // Generate an ID for each message, we can find them later
        var msgId = Math.round(Math.random()*(899999999999)+100000000000);
        var now = new Date();
        var $newMessage =
            $('<div class="message '
                + (PMA_console.config.alwaysExpand ? 'expanded' : 'collapsed')
                +'" msgid="' + msgId + '"><div class="action_content"></div></div>');
        switch(msgType) {
            case 'query':
                $newMessage.append('<div class="query highlighted"></div>');
                if(PMA_consoleInput._codemirror) {
                    CodeMirror.runMode(msgString,
                        'text/x-sql', $newMessage.children('.query')[0]);
                } else {
                    $newMessage.children('.query').text(msgString);
                }
                $newMessage.children('.action_content')
                    .append(PMA_console.$consoleTemplates.children('.query_actions').html());
                break;
            default:
            case 'normal':
                $newMessage.append('<div>' + msgString + '</div>');
        }
        PMA_consoleMessages._msgEventBinds($newMessage);
        $newMessage.find('span.text.query_time span')
            .text(now.getHours() + ':' + now.getMinutes() + ':' + now.getSeconds())
            .parent().attr('title', now);
        return {message_id: msgId,
                $message: $newMessage.appendTo('#pma_console .content .console_message_container')};
    },
    /**
     * Used for log new query
     *
     * @param string queryData Struct should be
     * {sql_query: "Query string", db: "Target DB", table: "Target Table"}
     * @param string state Message state
     * @return object, {message_id: string message id, $message: JQuery object}
     */
    appendQuery: function(queryData, state) {
        var targetMessage = PMA_consoleMessages.append(queryData.sql_query, 'query');
        if(! targetMessage) {
            return false;
        }
        if(queryData.db && queryData.table) {
            targetMessage.$message.attr('targetdb', queryData.db);
            targetMessage.$message.attr('targettable', queryData.table);
            targetMessage.$message.find('.text.targetdb span').text(queryData.db);
        }
        if(PMA_console.isSelect(queryData.sql_query)) {
            targetMessage.$message.addClass('select');
        }
        switch(state) {
            case 'failed':
                targetMessage.$message.addClass('failed');
                break;
            case 'successed':
                targetMessage.$message.addClass('successed');
                break;
            default:
            case 'pending':
                targetMessage.$message.addClass('pending');
        }
        return targetMessage;
    },
    _msgEventBinds: function($targetMessage) {
        // Leave unbinded elements, remove binded.
        $targetMessage = $targetMessage.filter(':not(.binded)');
        if($targetMessage.length === 0) {
            return;
        }
        $targetMessage.addClass('binded');

        $targetMessage.find('.action.expand').click(function () {
            $(this).closest('.message').removeClass('collapsed');
            $(this).closest('.message').addClass('expanded');
        });
        $targetMessage.find('.action.collapse').click(function () {
            $(this).closest('.message').addClass('collapsed');
            $(this).closest('.message').removeClass('expanded');
        });
        $targetMessage.find('.action.edit').click(function () {
            PMA_consoleInput.setText($(this).parent().siblings('.query').text());
            PMA_consoleInput.focus();
        });
        $targetMessage.find('.action.requery').click(function () {
            var query = $(this).parent().siblings('.query').text();
            var $message = $(this).closest('.message');
            if(confirm(PMA_messages.strConsoleRequeryConfirm + '\n'
                + (query.length<100 ? query : query.slice(0, 100) + '...'))) {
                PMA_console.execute(query, {db: $message.attr('targetdb'), table: $message.attr('targettable')});
            }
        });
        $targetMessage.find('.action.bookmark').click(function () {
            var query = $(this).parent().siblings('.query').text();
            var $message = $(this).closest('.message');
            PMA_consoleBookmarks.addBookmark(query, $message.attr('targetdb'));
            PMA_console.showCard('#pma_bookmarks .card.add');
        });
        $targetMessage.find('.action.edit_bookmark').click(function () {
            var query = $(this).parent().siblings('.query').text();
            var $message = $(this).closest('.message');
            var isShared = $message.find('span.bookmark_label').hasClass('shared');
            var label = $message.find('span.bookmark_label').text();
            PMA_consoleBookmarks.addBookmark(query, $message.attr('targetdb'), label, isShared);
            PMA_console.showCard('#pma_bookmarks .card.add');
        });
        $targetMessage.find('.action.delete_bookmark').click(function () {
            var $message = $(this).closest('.message');
            if(confirm(PMA_messages.strConsoleDeleteBookmarkConfirm + '\n' + $message.find('.bookmark_label').text())) {
                $.post('import.php',
                    {token: PMA_commonParams.get('token'),
                    server: PMA_commonParams.get('server'),
                    action_bookmark: 2,
                    ajax_request: true,
                    id_bookmark: $message.attr('bookmarkid')},
                    function () {
                        PMA_consoleBookmarks.refresh();
                    });
            }
        });
        $targetMessage.find('.action.profiling').click(function () {
            var $message = $(this).closest('.message');
            PMA_console.execute($(this).parent().siblings('.query').text(),
                {db: $message.attr('targetdb'),
                table: $message.attr('targettable'),
                profiling: true});
        });
        $targetMessage.find('.action.explain').click(function () {
            var $message = $(this).closest('.message');
            PMA_console.execute('EXPLAIN ' + $(this).parent().siblings('.query').text(),
                {db: $message.attr('targetdb'),
                table: $message.attr('targettable')});
        });
        if(PMA_consoleInput._codemirror) {
            $targetMessage.find('.query:not(.highlighted)').each(function(index, elem) {
                    CodeMirror.runMode($(elem).text(),
                        'text/x-sql', elem);
                    $(this).addClass('highlighted');
                });
        }
    },
    msgAppend: function(msgId, msgString, msgType) {
        var $targetMessage = $('#pma_console .content .console_message_container .message[msgid=' + msgId +']');
        if($targetMessage.length === 0 || isNaN(parseInt(msgId)) || typeof(msgString) !== 'string') {
            return false;
        }
        $targetMessage.append('<div>' + msgString + '</div>');
    },
    updateQuery: function(msgId, isSuccessed, queryData) {
        var $targetMessage = $('#pma_console .console_message_container .message[msgid=' + parseInt(msgId) +']');
        if($targetMessage.length === 0 || isNaN(parseInt(msgId))) {
            return false;
        }
        $targetMessage.removeClass('pending failed successed');
        if(isSuccessed) {
            $targetMessage.addClass('successed');
            if(queryData) {
                $targetMessage.children('.query').text('');
                $targetMessage.removeClass('select');
                if(PMA_console.isSelect(queryData.sql_query)) {
                    $targetMessage.addClass('select');
                }
                if(PMA_consoleInput._codemirror) {
                    CodeMirror.runMode(queryData.sql_query, 'text/x-sql', $targetMessage.children('.query')[0]);
                } else {
                    $targetMessage.children('.query').text(queryData.sql_query);
                }
                $targetMessage.attr('targetdb', queryData.db);
                $targetMessage.attr('targettable', queryData.table);
                $targetMessage.find('.text.targetdb span').text(queryData.db);
            }
        } else {
            $targetMessage.addClass('failed');
        }
    },
    /**
     * Used for console messages initialize
     *
     * @return void
     */
    initialize: function() {
        PMA_consoleMessages._msgEventBinds($('#pma_console .message:not(.binded)'));
        if(PMA_console.config.startHistory) {
            PMA_consoleMessages.showHistory();
        }
    }
};


/**
 * Console bookmarks card, and bookmarks items management object
 */
var PMA_consoleBookmarks = {
    _bookmarks: [],
    addBookmark: function (queryString, targetDb, label, isShared, id) {
        $('#pma_bookmarks .add [name=shared]').prop('checked', false);
        $('#pma_bookmarks .add [name=label]').val('');
        $('#pma_bookmarks .add [name=targetdb]').val('');
        $('#pma_bookmarks .add [name=id_bookmark]').val('');
        PMA_consoleInput.setText('', 'bookmark');

        switch(arguments.length) {
            case 4:
                $('#pma_bookmarks .add [name=shared]').prop('checked', isShared);
            case 3:
                $('#pma_bookmarks .add [name=label]').val(label);
            case 2:
                $('#pma_bookmarks .add [name=targetdb]').val(targetDb);
            case 1:
                PMA_consoleInput.setText(queryString, 'bookmark');
            default:
                break;
        }
    },
    refresh: function () {
        $.get('import.php',
            {ajax_request: true,
            token: PMA_commonParams.get('token'),
            server: PMA_commonParams.get('server'),
            console_bookmark_refresh: 'refresh'},
            function(data) {
                if(data.console_message_bookmark) {
                    $('#pma_bookmarks .content.bookmark').html(data.console_message_bookmark);
                    PMA_consoleMessages._msgEventBinds($('#pma_bookmarks .message:not(.binded)'));
                }
            });
    },
    /**
     * Used for console bookmarks initialize
     * message events are already binded by PMA_consoleMsg._msgEventBinds
     *
     * @return void
     */
    initialize: function() {
        if($('#pma_bookmarks').length === 0) {
            return;
        }
        $('#pma_console .button.bookmarks').click(function() {
            PMA_console.showCard('#pma_bookmarks');
        });
        $('#pma_bookmarks .button.add').click(function() {
            PMA_console.showCard('#pma_bookmarks .card.add');
        });
        $('#pma_bookmarks .card.add [name=submit]').click(function () {
            if ($('#pma_bookmarks .card.add [name=label]').val().length === 0
                || PMA_consoleInput.getText('bookmark').length === 0)
            {
                alert(PMA_messages.strFormEmpty);
                return;
            }
            $(this).prop('disabled', true);
            $.post('import.php',
                {token: PMA_commonParams.get('token'),
                ajax_request: true,
                console_bookmark_add: 'true',
                label: $('#pma_bookmarks .card.add [name=label]').val(),
                server: PMA_commonParams.get('server'),
                db: $('#pma_bookmarks .card.add [name=targetdb]').val(),
                bookmark_query: PMA_consoleInput.getText('bookmark'),
                shared: $('#pma_bookmarks .card.add [name=shared]').prop('checked')},
                function () {
                    PMA_consoleBookmarks.refresh();
                    $('#pma_bookmarks .card.add [name=submit]').prop('disabled', false);
                    PMA_console.hideCard($('#pma_bookmarks .card.add'));
                });
        });
        $('#pma_console .button.refresh').click(function() {
            PMA_consoleBookmarks.refresh();
        });
    }
};

/**
 * Executed on page load
 */
$(function () {
    PMA_console.initialize();
});
;

/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Functions used in configuration forms and on user preferences pages
 */

/**
 * Unbind all event handlers before tearing down a page
 */
AJAX.registerTeardown('config.js', function () {
    $('input[id], select[id], textarea[id]').unbind('change').unbind('keyup');
    $('input[type=button][name=submit_reset]').unbind('click');
    $('div.tabs_contents').undelegate();
    $('#import_local_storage, #export_local_storage').unbind('click');
    $('form.prefs-form').unbind('change').unbind('submit');
    $(document).off('click', 'div.click-hide-message');
    $('#prefs_autoload').find('a').unbind('click');
});

AJAX.registerOnload('config.js', function () {
    $('#topmenu2').find('li.active a').attr('rel', 'samepage');
    $('#topmenu2').find('li:not(.active) a').attr('rel', 'newpage');
});

// default values for fields
var defaultValues = {};

/**
 * Returns field type
 *
 * @param {Element} field
 */
function getFieldType(field)
{
    var $field = $(field);
    var tagName = $field.prop('tagName');
    if (tagName == 'INPUT') {
        return $field.attr('type');
    } else if (tagName == 'SELECT') {
        return 'select';
    } else if (tagName == 'TEXTAREA') {
        return 'text';
    }
    return '';
}

/**
 * Sets field value
 *
 * value must be of type:
 * o undefined (omitted) - restore default value (form default, not PMA default)
 * o String - if field_type is 'text'
 * o boolean - if field_type is 'checkbox'
 * o Array of values - if field_type is 'select'
 *
 * @param {Element} field
 * @param {String}  field_type  see {@link #getFieldType}
 * @param {String|Boolean}  [value]
 */
function setFieldValue(field, field_type, value)
{
    var $field = $(field);
    switch (field_type) {
    case 'text':
    case 'number':
        $field.val(value !== undefined ? value : $field.attr('defaultValue'));
        break;
    case 'checkbox':
        $field.prop('checked', (value !== undefined ? value : $field.attr('defaultChecked')));
        break;
    case 'select':
        var options = $field.prop('options');
        var i, imax = options.length;
        if (value === undefined) {
            for (i = 0; i < imax; i++) {
                options[i].selected = options[i].defaultSelected;
            }
        } else {
            for (i = 0; i < imax; i++) {
                options[i].selected = (value.indexOf(options[i].value) != -1);
            }
        }
        break;
    }
    markField($field);
}

/**
 * Gets field value
 *
 * Will return one of:
 * o String - if type is 'text'
 * o boolean - if type is 'checkbox'
 * o Array of values - if type is 'select'
 *
 * @param {Element} field
 * @param {String}  field_type returned by {@link #getFieldType}
 * @type Boolean|String|String[]
 */
function getFieldValue(field, field_type)
{
    var $field = $(field);
    switch (field_type) {
    case 'text':
    case 'number':
        return $field.prop('value');
    case 'checkbox':
        return $field.prop('checked');
    case 'select':
        var options = $field.prop('options');
        var i, imax = options.length, items = [];
        for (i = 0; i < imax; i++) {
            if (options[i].selected) {
                items.push(options[i].value);
            }
        }
        return items;
    }
    return null;
}

/**
 * Returns values for all fields in fieldsets
 */
function getAllValues()
{
    var $elements = $('fieldset input, fieldset select, fieldset textarea');
    var values = {};
    var type, value;
    for (var i = 0; i < $elements.length; i++) {
        type = getFieldType($elements[i]);
        value = getFieldValue($elements[i], type);
        if (typeof value != 'undefined') {
            // we only have single selects, fatten array
            if (type == 'select') {
                value = value[0];
            }
            values[$elements[i].name] = value;
        }
    }
    return values;
}

/**
 * Checks whether field has its default value
 *
 * @param {Element} field
 * @param {String}  type
 * @return boolean
 */
function checkFieldDefault(field, type)
{
    var $field = $(field);
    var field_id = $field.attr('id');
    if (typeof defaultValues[field_id] == 'undefined') {
        return true;
    }
    var isDefault = true;
    var currentValue = getFieldValue($field, type);
    if (type != 'select') {
        isDefault = currentValue == defaultValues[field_id];
    } else {
        // compare arrays, will work for our representation of select values
        if (currentValue.length != defaultValues[field_id].length) {
            isDefault = false;
        }
        else {
            for (var i = 0; i < currentValue.length; i++) {
                if (currentValue[i] != defaultValues[field_id][i]) {
                    isDefault = false;
                    break;
                }
            }
        }
    }
    return isDefault;
}

/**
 * Returns element's id prefix
 * @param {Element} element
 */
function getIdPrefix(element)
{
    return $(element).attr('id').replace(/[^-]+$/, '');
}

// ------------------------------------------------------------------
// Form validation and field operations
//

// form validator assignments
var validate = {};

// form validator list
var validators = {
    // regexp: numeric value
    _regexp_numeric: /^[0-9]+$/,
    // regexp: extract parts from PCRE expression
    _regexp_pcre_extract: /(.)(.*)\1(.*)?/,
    /**
     * Validates positive number
     *
     * @param {boolean} isKeyUp
     */
    PMA_validatePositiveNumber: function (isKeyUp) {
        if (isKeyUp && this.value === '') {
            return true;
        }
        var result = this.value != '0' && validators._regexp_numeric.test(this.value);
        return result ? true : PMA_messages.error_nan_p;
    },
    /**
     * Validates non-negative number
     *
     * @param {boolean} isKeyUp
     */
    PMA_validateNonNegativeNumber: function (isKeyUp) {
        if (isKeyUp && this.value === '') {
            return true;
        }
        var result = validators._regexp_numeric.test(this.value);
        return result ? true : PMA_messages.error_nan_nneg;
    },
    /**
     * Validates port number
     *
     * @param {boolean} isKeyUp
     */
    PMA_validatePortNumber: function (isKeyUp) {
        if (this.value === '') {
            return true;
        }
        var result = validators._regexp_numeric.test(this.value) && this.value != '0';
        return result && this.value <= 65535 ? true : PMA_messages.error_incorrect_port;
    },
    /**
     * Validates value according to given regular expression
     *
     * @param {boolean} isKeyUp
     * @param {string}  regexp
     */
    PMA_validateByRegex: function (isKeyUp, regexp) {
        if (isKeyUp && this.value === '') {
            return true;
        }
        // convert PCRE regexp
        var parts = regexp.match(validators._regexp_pcre_extract);
        var valid = this.value.match(new RegExp(parts[2], parts[3])) !== null;
        return valid ? true : PMA_messages.error_invalid_value;
    },
    /**
     * Validates upper bound for numeric inputs
     *
     * @param {boolean} isKeyUp
     * @param {int} max_value
     */
    PMA_validateUpperBound: function (isKeyUp, max_value) {
        var val = parseInt(this.value, 10);
        if (isNaN(val)) {
            return true;
        }
        return val <= max_value ? true : PMA_sprintf(PMA_messages.error_value_lte, max_value);
    },
    // field validators
    _field: {
    },
    // fieldset validators
    _fieldset: {
    }
};

/**
 * Registers validator for given field
 *
 * @param {String}  id       field id
 * @param {String}  type     validator (key in validators object)
 * @param {boolean} onKeyUp  whether fire on key up
 * @param {Array}   params   validation function parameters
 */
function validateField(id, type, onKeyUp, params)
{
    if (typeof validators[type] == 'undefined') {
        return;
    }
    if (typeof validate[id] == 'undefined') {
        validate[id] = [];
    }
    validate[id].push([type, params, onKeyUp]);
}

/**
 * Returns validation functions associated with form field
 *
 * @param {String}  field_id     form field id
 * @param {boolean} onKeyUpOnly  see validateField
 * @type Array
 * @return array of [function, parameters to be passed to function]
 */
function getFieldValidators(field_id, onKeyUpOnly)
{
    // look for field bound validator
    var name = field_id.match(/[^-]+$/)[0];
    if (typeof validators._field[name] != 'undefined') {
        return [[validators._field[name], null]];
    }

    // look for registered validators
    var functions = [];
    if (typeof validate[field_id] != 'undefined') {
        // validate[field_id]: array of [type, params, onKeyUp]
        for (var i = 0, imax = validate[field_id].length; i < imax; i++) {
            if (onKeyUpOnly && !validate[field_id][i][2]) {
                continue;
            }
            functions.push([validators[validate[field_id][i][0]], validate[field_id][i][1]]);
        }
    }

    return functions;
}

/**
 * Displays errors for given form fields
 *
 * WARNING: created DOM elements must be identical with the ones made by
 * display_input() in FormDisplay.tpl.php!
 *
 * @param {Object} error_list list of errors in the form {field id: error array}
 */
function displayErrors(error_list)
{
    var tempIsEmpty = function (item) {
        return item !== '';
    };

    for (var field_id in error_list) {
        var errors = error_list[field_id];
        var $field = $('#' + field_id);
        var isFieldset = $field.attr('tagName') == 'FIELDSET';
        var $errorCnt;
        if (isFieldset) {
            $errorCnt = $field.find('dl.errors');
        } else {
            $errorCnt = $field.siblings('.inline_errors');
        }

        // remove empty errors (used to clear error list)
        errors = $.grep(errors, tempIsEmpty);

        // CSS error class
        if (!isFieldset) {
            // checkboxes uses parent <span> for marking
            var $fieldMarker = ($field.attr('type') == 'checkbox') ? $field.parent() : $field;
            $fieldMarker[errors.length ? 'addClass' : 'removeClass']('field-error');
        }

        if (errors.length) {
            // if error container doesn't exist, create it
            if ($errorCnt.length === 0) {
                if (isFieldset) {
                    $errorCnt = $('<dl class="errors" />');
                    $field.find('table').before($errorCnt);
                } else {
                    $errorCnt = $('<dl class="inline_errors" />');
                    $field.closest('td').append($errorCnt);
                }
            }

            var html = '';
            for (var i = 0, imax = errors.length; i < imax; i++) {
                html += '<dd>' + errors[i] + '</dd>';
            }
            $errorCnt.html(html);
        } else if ($errorCnt !== null) {
            // remove useless error container
            $errorCnt.remove();
        }
    }
}

/**
 * Validates fieldset and puts errors in 'errors' object
 *
 * @param {Element} fieldset
 * @param {boolean} isKeyUp
 * @param {Object}  errors
 */
function validate_fieldset(fieldset, isKeyUp, errors)
{
    var $fieldset = $(fieldset);
    if ($fieldset.length && typeof validators._fieldset[$fieldset.attr('id')] != 'undefined') {
        var fieldset_errors = validators._fieldset[$fieldset.attr('id')].apply($fieldset[0], [isKeyUp]);
        for (var field_id in fieldset_errors) {
            if (typeof errors[field_id] == 'undefined') {
                errors[field_id] = [];
            }
            if (typeof fieldset_errors[field_id] == 'string') {
                fieldset_errors[field_id] = [fieldset_errors[field_id]];
            }
            $.merge(errors[field_id], fieldset_errors[field_id]);
        }
    }
}

/**
 * Validates form field and puts errors in 'errors' object
 *
 * @param {Element} field
 * @param {boolean} isKeyUp
 * @param {Object}  errors
 */
function validate_field(field, isKeyUp, errors)
{
    var args, result;
    var $field = $(field);
    var field_id = $field.attr('id');
    errors[field_id] = [];
    var functions = getFieldValidators(field_id, isKeyUp);
    for (var i = 0; i < functions.length; i++) {
        if (typeof functions[i][1] !== 'undefined' && functions[i][1] !== null) {
            args = functions[i][1].slice(0);
        } else {
            args = [];
        }
        args.unshift(isKeyUp);
        result = functions[i][0].apply(field[0], args);
        if (result !== true) {
            if (typeof result == 'string') {
                result = [result];
            }
            $.merge(errors[field_id], result);
        }
    }
}

/**
 * Validates form field and parent fieldset
 *
 * @param {Element} field
 * @param {boolean} isKeyUp
 */
function validate_field_and_fieldset(field, isKeyUp)
{
    var $field = $(field);
    var errors = {};
    validate_field($field, isKeyUp, errors);
    validate_fieldset($field.closest('fieldset'), isKeyUp, errors);
    displayErrors(errors);
}

/**
 * Marks field depending on its value (system default or custom)
 *
 * @param {Element} field
 */
function markField(field)
{
    var $field = $(field);
    var type = getFieldType($field);
    var isDefault = checkFieldDefault($field, type);

    // checkboxes uses parent <span> for marking
    var $fieldMarker = (type == 'checkbox') ? $field.parent() : $field;
    setRestoreDefaultBtn($field, !isDefault);
    $fieldMarker[isDefault ? 'removeClass' : 'addClass']('custom');
}

/**
 * Enables or disables the "restore default value" button
 *
 * @param {Element} field
 * @param {boolean} display
 */
function setRestoreDefaultBtn(field, display)
{
    var $el = $(field).closest('td').find('.restore-default img');
    $el[display ? 'show' : 'hide']();
}

AJAX.registerOnload('config.js', function () {
    // register validators and mark custom values
    var $elements = $('input[id], select[id], textarea[id]');
    $('input[id], select[id], textarea[id]').each(function () {
        markField(this);
        var $el = $(this);
        $el.bind('change', function () {
            validate_field_and_fieldset(this, false);
            markField(this);
        });
        var tagName = $el.attr('tagName');
        // text fields can be validated after each change
        if (tagName == 'INPUT' && $el.attr('type') == 'text') {
            $el.keyup(function () {
                validate_field_and_fieldset($el, true);
                markField($el);
            });
        }
        // disable textarea spellcheck
        if (tagName == 'TEXTAREA') {
            $el.attr('spellcheck', false);
        }
    });

    // check whether we've refreshed a page and browser remembered modified
    // form values
    var $check_page_refresh = $('#check_page_refresh');
    if ($check_page_refresh.length === 0 || $check_page_refresh.val() == '1') {
        // run all field validators
        var errors = {};
        for (var i = 0; i < $elements.length; i++) {
            validate_field($elements[i], false, errors);
        }
        // run all fieldset validators
        $('fieldset').each(function () {
            validate_fieldset(this, false, errors);
        });

        displayErrors(errors);
    } else if ($check_page_refresh) {
        $check_page_refresh.val('1');
    }
});

//
// END: Form validation and field operations
// ------------------------------------------------------------------

// ------------------------------------------------------------------
// Tabbed forms
//

/**
 * Sets active tab
 *
 * @param {String} tab_id
 */
function setTab(tab_id)
{
    $('ul.tabs li').removeClass('active').find('a[href=#' + tab_id + ']').parent().addClass('active');
    $('div.tabs_contents fieldset').hide().filter('#' + tab_id).show();
    location.hash = 'tab_' + tab_id;
    $('form.config-form input[name=tab_hash]').val(location.hash);
}

AJAX.registerOnload('config.js', function () {
    var $tabs = $('ul.tabs');
    if (!$tabs.length) {
        return;
    }
    // add tabs events and activate one tab (the first one or indicated by location hash)
    $tabs.find('a')
        .click(function (e) {
            e.preventDefault();
            setTab($(this).attr('href').substr(1));
        })
        .filter(':first')
        .parent()
        .addClass('active');
    $('div.tabs_contents fieldset').hide().filter(':first').show();

    // tab links handling, check each 200ms
    // (works with history in FF, further browser support here would be an overkill)
    var prev_hash;
    var tab_check_fnc = function () {
        if (location.hash != prev_hash) {
            prev_hash = location.hash;
            if (location.hash.match(/^#tab_.+/) && $('#' + location.hash.substr(5)).length) {
                setTab(location.hash.substr(5));
            }
        }
    };
    tab_check_fnc();
    setInterval(tab_check_fnc, 200);
});

//
// END: Tabbed forms
// ------------------------------------------------------------------

// ------------------------------------------------------------------
// Form reset buttons
//

AJAX.registerOnload('config.js', function () {
    $('input[type=button][name=submit_reset]').click(function () {
        var fields = $(this).closest('fieldset').find('input, select, textarea');
        for (var i = 0, imax = fields.length; i < imax; i++) {
            setFieldValue(fields[i], getFieldType(fields[i]));
        }
    });
});

//
// END: Form reset buttons
// ------------------------------------------------------------------

// ------------------------------------------------------------------
// "Restore default" and "set value" buttons
//

/**
 * Restores field's default value
 *
 * @param {String} field_id
 */
function restoreField(field_id)
{
    var $field = $('#' + field_id);
    if ($field.length === 0 || defaultValues[field_id] === undefined) {
        return;
    }
    setFieldValue($field, getFieldType($field), defaultValues[field_id]);
}

AJAX.registerOnload('config.js', function () {
    $('div.tabs_contents')
        .delegate('.restore-default, .set-value', 'mouseenter', function () {
            $(this).css('opacity', 1);
        })
        .delegate('.restore-default, .set-value', 'mouseleave', function () {
            $(this).css('opacity', 0.25);
        })
        .delegate('.restore-default, .set-value', 'click', function (e) {
            e.preventDefault();
            var href = $(this).attr('href');
            var field_sel;
            if ($(this).hasClass('restore-default')) {
                field_sel = href;
                restoreField(field_sel.substr(1));
            } else {
                field_sel = href.match(/^[^=]+/)[0];
                var value = href.match(/\=(.+)$/)[1];
                setFieldValue($(field_sel), 'text', value);
            }
            $(field_sel).trigger('change');
        })
        .find('.restore-default, .set-value')
        // inline-block for IE so opacity inheritance works
        .css({display: 'inline-block', opacity: 0.25});
});

//
// END: "Restore default" and "set value" buttons
// ------------------------------------------------------------------

// ------------------------------------------------------------------
// User preferences import/export
//

AJAX.registerOnload('config.js', function () {
    offerPrefsAutoimport();
    var $radios = $('#import_local_storage, #export_local_storage');
    if (!$radios.length) {
        return;
    }

    // enable JavaScript dependent fields
    $radios
        .prop('disabled', false)
        .add('#export_text_file, #import_text_file')
        .click(function () {
            var enable_id = $(this).attr('id');
            var disable_id;
            if (enable_id.match(/local_storage$/)) {
                disable_id = enable_id.replace(/local_storage$/, 'text_file');
            } else {
                disable_id = enable_id.replace(/text_file$/, 'local_storage');
            }
            $('#opts_' + disable_id).addClass('disabled').find('input').prop('disabled', true);
            $('#opts_' + enable_id).removeClass('disabled').find('input').prop('disabled', false);
        });

    // detect localStorage state
    var ls_supported = window.localStorage || false;
    var ls_exists = ls_supported ? (window.localStorage.config || false) : false;
    $('div.localStorage-' + (ls_supported ? 'un' : '') + 'supported').hide();
    $('div.localStorage-' + (ls_exists ? 'empty' : 'exists')).hide();
    if (ls_exists) {
        updatePrefsDate();
    }
    $('form.prefs-form').change(function () {
        var $form = $(this);
        var disabled = false;
        if (!ls_supported) {
            disabled = $form.find('input[type=radio][value$=local_storage]').prop('checked');
        } else if (!ls_exists && $form.attr('name') == 'prefs_import' &&
            $('#import_local_storage')[0].checked
            ) {
            disabled = true;
        }
        $form.find('input[type=submit]').prop('disabled', disabled);
    }).submit(function (e) {
        var $form = $(this);
        if ($form.attr('name') == 'prefs_export' && $('#export_local_storage')[0].checked) {
            e.preventDefault();
            // use AJAX to read JSON settings and save them
            savePrefsToLocalStorage($form);
        } else if ($form.attr('name') == 'prefs_import' && $('#import_local_storage')[0].checked) {
            // set 'json' input and submit form
            $form.find('input[name=json]').val(window.localStorage.config);
        }
    });

    $(document).on('click', 'div.click-hide-message', function () {
        $(this)
        .hide()
        .parent('.group')
        .css('height', '')
        .next('form')
        .show();
    });
});

/**
 * Saves user preferences to localStorage
 *
 * @param {Element} form
 */
function savePrefsToLocalStorage(form)
{
    $form = $(form);
    var submit = $form.find('input[type=submit]');
    submit.prop('disabled', true);
    $.ajax({
        url: 'prefs_manage.php',
        cache: false,
        type: 'POST',
        data: {
            ajax_request: true,
            server: $form.find('input[name=server]').val(),
            token: $form.find('input[name=token]').val(),
            submit_get_json: true
        },
        success: function (data) {
            if (typeof data !== 'undefined' && data.success === true) {
                window.localStorage.config = data.prefs;
                window.localStorage.config_mtime = data.mtime;
                window.localStorage.config_mtime_local = (new Date()).toUTCString();
                updatePrefsDate();
                $('div.localStorage-empty').hide();
                $('div.localStorage-exists').show();
                var group = $form.parent('.group');
                group.css('height', group.height() + 'px');
                $form.hide('fast');
                $form.prev('.click-hide-message').show('fast');
            } else {
                PMA_ajaxShowMessage(data.error);
            }
        },
        complete: function () {
            submit.prop('disabled', false);
        }
    });
}

/**
 * Updates preferences timestamp in Import form
 */
function updatePrefsDate()
{
    var d = new Date(window.localStorage.config_mtime_local);
    var msg = PMA_messages.strSavedOn.replace(
        '@DATE@',
        PMA_formatDateTime(d)
    );
    $('#opts_import_local_storage div.localStorage-exists').html(msg);
}

/**
 * Prepares message which informs that localStorage preferences are available and can be imported
 */
function offerPrefsAutoimport()
{
    var has_config = (window.localStorage || false) && (window.localStorage.config || false);
    var $cnt = $('#prefs_autoload');
    if (!$cnt.length || !has_config) {
        return;
    }
    $cnt.find('a').click(function (e) {
        e.preventDefault();
        var $a = $(this);
        if ($a.attr('href') == '#no') {
            $cnt.remove();
            $.post('index.php', {
                token: $cnt.find('input[name=token]').val(),
                prefs_autoload: 'hide'
            });
            return;
        }
        $cnt.find('input[name=json]').val(window.localStorage.config);
        $cnt.find('form').submit();
    });
    $cnt.show();
}

//
// END: User preferences import/export
// ------------------------------------------------------------------
;

