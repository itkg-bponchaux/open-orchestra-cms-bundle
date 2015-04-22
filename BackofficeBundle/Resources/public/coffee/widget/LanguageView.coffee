LanguageView = OrchestraView.extend(

  initialize: (options) ->
    @events = {}
    @events['click a.change-language'] = 'changeLanguage'
    @options = options
    @loadTemplates [
      "widgetLanguage"
    ]
    return

  render: ->
    @setElement @renderTemplate('widgetLanguage',
      language: @options.language
      currentLanguage: @options.currentLanguage.language
    )
    @options.domContainer.append @$el
    return

  changeLanguage: (event) ->
    event.preventDefault()
    redirectUrl = appRouter.generateUrl(@options.currentLanguage.path, appRouter.addParametersToRoute(
      language: $(event.currentTarget).data('language')
    ))
    Backbone.history.navigate(redirectUrl, {trigger: true})
)
