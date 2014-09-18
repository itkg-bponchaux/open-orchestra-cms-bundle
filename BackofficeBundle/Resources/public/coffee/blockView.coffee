BlockView = Backbone.View.extend(
  tagName: 'li'
  className: 'ui-model-blocks'
  events:
    'click i.block-param': 'paramBlock'
    'click i.block-remove': 'removeBlock'
  initialize: (options) ->
    @block = options.block
    @height = options.height
    @direction = options.direction || 'height'
    @displayClass = (if @direction is "width" then "inline" else "block")
    _.bindAll this, "render"
    @blockTemplate = _.template($('#blockView').html())
    return
  paramBlock: (event) ->
    $('.modal-title').text 'Please wait ...'
    view = new adminFormView(url: @block.get('links')._self_form)
  removeBlock: (event) ->
    $('.modal-title').text 'Please wait ...'
    view = new adminFormView(url: @block.get('links')._self_remove, method: 'POST')
  render: ->
    $(@el).attr('style', @direction + ':' + @height + '%').addClass(@displayClass).html @blockTemplate(
      block: @block
      height: @height
    )
    this
)
