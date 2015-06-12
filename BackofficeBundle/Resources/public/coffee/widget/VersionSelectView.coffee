VersionSelectView = OrchestraView.extend(
  tagName: "select"

  events:
    'change': 'changeVersion'

  initialize: (options) ->
    @options = options
    @loadTemplates [
      "OpenOrchestraBackofficeBundle:BackOffice:Underscore/widgetVersionSelect"
      "OpenOrchestraBackofficeBundle:BackOffice:Underscore/widgetVersion"
    ]
    return

  render: ->
    @setElement @renderTemplate('OpenOrchestraBackofficeBundle:BackOffice:Underscore/widgetVersionSelect')
    @options.domContainer.replaceWith @$el
    for version of @options.versions
      versionElement = new VersionviewModel
      versionElement.set @options.versions[version]
      new VersionView(
        element: versionElement
        currentVersion: @options.currentVersion
        domContainer: @$el
      )
    return

  changeVersion: (event) ->
    event.preventDefault()
    displayLoader()
    redirectUrl = appRouter.generateUrl(@options.currentVersion.path, appRouter.addParametersToRoute(
      version: event.currentTarget.value
      language: @options.currentVersion.language
    ))
    Backbone.history.navigate(redirectUrl, {trigger: true})
)
