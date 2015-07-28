GSTemplateView = OrchestraView.extend(
  extendView : [ 'commonPage']

  initialize: (options) ->
    @options = @reduceOption(options, [
      'template'
      'domContainer'
    ])
    @options.configuration = @options.template
    @options.entityType = 'template'
    @options.published = false
    @loadTemplates [
      "OpenOrchestraBackofficeBundle:BackOffice:Underscore/gsTemplateView"
    ]
    return

  render: ->
    @setElement @renderTemplate('OpenOrchestraBackofficeBundle:BackOffice:Underscore/gsTemplateView',
      template: @options.template
    )
    @options.domContainer.html @$el
    $('.js-widget-title', @$el).html $('#generated-title', @$el).html()
    @addConfigurationButton('template')
    return

)
