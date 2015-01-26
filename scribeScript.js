require({
  paths: {
    'scribe': './bower_components/scribe/scribe',
    'scribe-plugin-blockquote-command': './bower_components/scribe-plugin-blockquote-command/scribe-plugin-blockquote-command',
    'scribe-plugin-code-command': './bower_components/scribe-plugin-code-command/scribe-plugin-code-command',
    'scribe-plugin-curly-quotes': './bower_components/scribe-plugin-curly-quotes/scribe-plugin-curly-quotes',
    'scribe-plugin-formatter-plain-text-convert-new-lines-to-html': './bower_components/scribe-plugin-formatter-plain-text-convert-new-lines-to-html/scribe-plugin-formatter-plain-text-convert-new-lines-to-html',
    'scribe-plugin-heading-command': './bower_components/scribe-plugin-heading-command/scribe-plugin-heading-command',
    'scribe-plugin-intelligent-unlink-command': './bower_components/scribe-plugin-intelligent-unlink-command/scribe-plugin-intelligent-unlink-command',
    'scribe-plugin-link-prompt-command': './bower_components/scribe-plugin-link-prompt-command/scribe-plugin-link-prompt-command',
    'scribe-plugin-sanitizer': './bower_components/scribe-plugin-sanitizer/scribe-plugin-sanitizer',
    'scribe-plugin-smart-lists': './bower_components/scribe-plugin-smart-lists/scribe-plugin-smart-lists',
    'scribe-plugin-toolbar': './bower_components/scribe-plugin-toolbar/scribe-plugin-toolbar'
  }
}, [
  'scribe',
  'scribe-plugin-blockquote-command',
  'scribe-plugin-code-command',
  'scribe-plugin-curly-quotes',
  'scribe-plugin-formatter-plain-text-convert-new-lines-to-html',
  'scribe-plugin-heading-command',
  'scribe-plugin-intelligent-unlink-command',
  'scribe-plugin-link-prompt-command',
  'scribe-plugin-sanitizer',
  'scribe-plugin-smart-lists',
  'scribe-plugin-toolbar'
], function (
  Scribe,
  scribePluginBlockquoteCommand,
  scribePluginCodeCommand,
  scribePluginCurlyQuotes,
  scribePluginFormatterPlainTextConvertNewLinesToHtml,
  scribePluginHeadingCommand,
  scribePluginIntelligentUnlinkCommand,
  scribePluginLinkPromptCommand,
  scribePluginSanitizer,
  scribePluginSmartLists,
  scribePluginToolbar
) {

  'use strict';

  var scribe = new Scribe(document.querySelector('.scribe'), { allowBlockElements: true });

  scribe.on('content-changed', updateHTML);

  function updateHTML() {
    document.querySelector('.scribe-html').value = scribe.getHTML();
  }

  /**
   * Plugins
   */

  scribe.use(scribePluginBlockquoteCommand());
  scribe.use(scribePluginCodeCommand());
  scribe.use(scribePluginHeadingCommand(2));
  scribe.use(scribePluginIntelligentUnlinkCommand());
  scribe.use(scribePluginLinkPromptCommand());
  scribe.use(scribePluginToolbar(document.querySelector('.toolbar')));
  scribe.use(scribePluginSmartLists());
  scribe.use(scribePluginCurlyQuotes());

  // Formatters
  scribe.use(scribePluginSanitizer({
    tags: {
      p: {},
      br: {},
      b: {},
      strong: {},
      i: {},
      strike: {},
      blockquote: {},
      code: {},
      ol: {},
      ul: {},
      li: {},
      a: { href: true },
      h2: {}
    }
  }));
  scribe.use(scribePluginFormatterPlainTextConvertNewLinesToHtml());
  scribe.setContent('Welcome, user.');

  $(document).ready(function () {
      $.post('/queryLocalMatch').done(function (data) {
        if(typeof data=='string') {
            $.notifyBar({
                cssClass: "error",
                delay: 4500,
                html: 'ERROR<br>Please choose a proper namespace/title referenced in the iFixit API documentation. <br>https://www.ifixit.com/api/2.0/doc/Wikis'
            });
        } else {
            scribe.setContent(data['contents_rendered']);
        }
      });
  });
    $('#load').click(function () {
        $.post('/loadText').done(function (data) {
        if(data == 'failure') {
            $.notifyBar({
                cssClass: "error",
                delay: 4500,
                html: "API refresh failed."
            });
        } else {
            scribe.setContent(data['contents_rendered']);
            $.notifyBar({
                cssClass: "success",
                html: "API refresh was successful."
            });
        }
        });
    });
    $('#save').click(function () {
        var results = {'contents_rendered':$(".scribe-html").val()};
        $.post('/insertDB', results).done(function (data) {
            if(data == 'failure') {
                $.notifyBar({
                    cssClass: "error",
                    delay: 4500,
                    html: "Database insertion failed."
                });
            } else {
                $.notifyBar({
                    cssClass: "success",
                    html: "Database insert was successful."
                });
            }
        });
    });
});
