NodeView = Backbone.View.extend(
  el: '#content'
  events:
    'click i#none' : 'clickButton'
    'click a.ajax-node-duplicate' : 'duplicateNode'
  initialize: (options) ->
    @node = options.node
    @vent = options.vent
    key = "click i." + @node.cid
    @events[key] = "clickButton"
    _.bindAll this, "render", "addAreaToView", "clickButton", "duplicateNode"
    @nodeTemplate = _.template($("#nodeView").html())
    @render()
    nav_page_height()
    return
  clickButton: (event) ->
    $('.modal-title').text @node.get('name')
    if @node.attributes.alias is ''
      view = new adminFormView(url: parentNode.data("url"), triggers: [{event: 'keyup input#node_name', name: 'refreshAlias', fct : refreshAlias}, {event: 'blur input#node_alias', name: 'stopRefreshAlias', fct : stopRefreshAlias}])
    else
      view = new adminFormView(url: parentNode.data("url"))
  duplicateNode: (event) ->
    event.preventDefault() #
    viewContext = this
    $.ajax
      url: @node.get('links')._self_duplicate
      method: 'POST'
    return
  render: ->
    $(@el).html @nodeTemplate(
      node: @node
    )
    $('.js-widget-title', @$el).text $('#generated-title', @$el).text()
    areaHeight = 100 / @node.get('areas').length if @node.get('areas').length > 0
    for area of @node.get('areas')
      @addAreaToView(@node.get('areas')[area], areaHeight)
    return
  addAreaToView: (area, areaHeight) ->
    areaElement = new Area
    areaElement.set area
    areaView = new AreaView(
      area: areaElement
      height: areaHeight
      node_id: @node.get('node_id')
    )
    this.$el.find('div[role="container"]').children('div').children('ul.ui-model-areas').append areaView.render().el
    return
)
