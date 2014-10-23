refreshUl = (ul) ->
  if ul isnt null
    childs = ul.children(':visible')
    nbrChildren = childs.length
    direction = if childs.filter(".inline").length > 0 then "width" else "height"
    childs.each ->
      $(this).css direction, (100 / nbrChildren) + "%"
      $(this).css (if direction is "height" then "width" else "height"), "100%"
      return
  return
makeSortable = (el, duplicate) ->
  $("ul.ui-model-blocks", el).sortable(
    connectWith: '#content div[role="container"] ul.ui-model-blocks',
    appendTo: 'body',
    tolerance: 'pointer',
    helper: (event, obj) ->
      helper = obj.clone()
      $('.panel-block', helper).removeClass('panel-block')
      $('.preview', helper).addClass('scroll')
      $("<div></div>").addClass("ui-model").append helper
    create: (event, ui)->
      @stockedUl = $(this)
      @duplicate = duplicate
      @evaluateRefreshable = ->
        not @duplicate or not $(this).is(@stockedUl)
      @refreshHelper = (ui) ->
        placeholder = ui.placeholder
        if @evaluateRefreshable()
          ui.placeholder.show()
        else
          ui.placeholder.hide()
          placeholder = ui.item
        $('li', ui.helper).height placeholder.height()
        $('li', ui.helper).width placeholder.width()
      if @duplicate
        @clone = $(this).clone()
    start: (event, ui)->
      if @duplicate
        ui.item.show()
        ui.placeholder.hide()
      else
        refreshUl @stockedUl
      @refreshHelper(ui)
    change: (event, ui) ->
      refreshUl @stockedUl  if @evaluateRefreshable()
      @stockedUl = ui.placeholder.parent()
      refreshUl @stockedUl  if @evaluateRefreshable()
      @refreshHelper(ui)
    stop: (event, ui)->
      if @duplicate and @clone
        $(this).replaceWith(@clone)
        makeSortable el, duplicate
  ).disableSelection()
  return
