GalleryCollectionView = OrchestraView.extend(
  events:
    'click #none': 'clickAdd'

  initialize: (options) ->
    @medias = options.medias
    @title = options.title
    @listUrl = options.listUrl
    @target = options.el
    key = 'click a.ajax-add-' + @cid
    @events[key] = 'clickAdd'
    key = 'click i.ajax-folder-' + @cid
    @events[key] = 'clickRedirect'
    _.bindAll this, "render"
    @loadTemplates [
      "galleryCollectionView",
      "galleryView"
    ]
    return

  render: ->
    $(@el).html @renderTemplate('galleryCollectionView',
      links: @medias.get('links')
      cid: @cid
    )
    $('.js-widget-title', @$el).text @title
    $('.js-widget-edit', @$el).html($('#generated-edit', @$el).html()).show()
    for mediaKey of @medias.get(@medias.get('collection_name'))
      @addElementToView (@medias.get(@medias.get('collection_name'))[mediaKey])
    $("img", @$el).each ->
      $(this).wrap("<div class=\"figure superbox-list\"></div>").after("<p class=\"caption\">" + $(this).attr("title") + "</p>")
    $(".figure").width $(this).find("img").width()
    $(".figure").mouseenter(->
      $(this).find(".caption").slideToggle("fast")
      return
    ).mouseleave ->
      $(this).find(".caption").slideToggle("fast")
      return

  addElementToView: (mediaData) ->
    mediaModel = new GalleryModel
    mediaModel.set mediaData
    view = new GalleryView(
      media: mediaModel
      title: @title
      listUrl: @listUrl
      el: this.$el.find('.superbox')
      target: @target
    )
    return

  clickAdd: (event) ->
    event.preventDefault()
    if $('#main .' + $(event.target).attr('class')).length
      displayLoader('div[role="container"]')
      Backbone.history.navigate('/add')
      title = @title
      listUrl = @listUrl
      $.ajax
        url: @medias.get('links')._self_add
        method: 'GET'
        success: (response) ->
          view = new FullPageFormView(
            html: response
            title: title
            listUrl: listUrl
          )

  clickRedirect: (event) ->
    event.preventDefault()
    $('.modal-title').text $(event.target).html()
    view = new adminFormView(
      url: @medias.get('links')._self_folder
      deleteurl: @medias.get('links')._self_delete
    )
)
