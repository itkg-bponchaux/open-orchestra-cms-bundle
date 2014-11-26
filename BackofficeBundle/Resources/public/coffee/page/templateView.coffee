TemplateView = OrchestraView.extend(
  el: '#content'

  events:
    'click i#none' : 'clickButton'

  initialize: (options) ->
    @template = options.template
    key = "click i." + @template.cid
    @events[key] = "clickButton"
    _.bindAll this, "render", "addAreaToView", "clickButton"
    @loadTemplates [
      "templateView"
      "areaView"
    ]
    return

  clickButton: (event) ->
    $('.modal-title').text @template.get('name')
    view = new adminFormView(
      url: @template.get('links')._self_form
      deleteurl: @template.get('links')._self_delete
    )

  render: ->
    $(@el).html @renderTemplate('templateView',
      template: @template
    )
    $('.js-widget-title', @$el).html $('#generated-title', @$el).html()
    for area of @template.get('areas')
      @addAreaToView(@template.get('areas')[area])
    return

  addAreaToView: (area) ->
    areaElement = new Area
    areaElement.set area
    areaView = new AreaView(
      area: areaElement,
      displayClass: (if @template.get("bo_direction") is "v" then "inline" else "block")
      el: this.$el.find('div[role="container"]').children('div').children('ul.ui-model-areas')
    )
    $("ul.ui-model-areas", @$el).each ->
      refreshUl $(this)
    return
)
