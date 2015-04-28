
showTemplate = (url)->
  $.ajax
    type: "GET"
    url: url
    success: (response) ->
      template = new Template
      template.set response
      view = new TemplateView(
        template: template
        inheritance : [ 'areaManagement' ]
        )
      appRouter.setCurrentMainView(view)
      return
  return
