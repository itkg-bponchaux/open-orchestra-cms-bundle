GalleryCollectionView = OrchestraView.extend(
  events:
    'click a.ajax-add': 'clickAdd'

  initialize: (options) ->
    @options = @reduceOption(options, [
      'medias'
      'title'
      'listUrl'
      'domContainer'
      'modal'
    ])
    @loadTemplates [
      "OpenOrchestraBackofficeBundle:BackOffice:Underscore/galleryCollectionView",
    ]
    return

  render: ->
    @setElement @renderTemplate('OpenOrchestraBackofficeBundle:BackOffice:Underscore/galleryCollectionView'
      links: @options.medias.get('links')
    )
    @options.domContainer.html @$el
    $('.js-widget-title', @options.domContainer).text @options.title
    if !@options.modal
      @addConfigurationButton()
      @addDeleteButton()
    for mediaKey of @options.medias.get(@options.medias.get('collection_name'))
      @addElementToView (@options.medias.get(@options.medias.get('collection_name'))[mediaKey])
    $(".figure").width @options.domContainer.find("img").width()
    $(".figure").mouseenter(->
      $(this).find(".caption").slideToggle(150)
      return
    ).mouseleave ->
      $(this).find(".caption").slideToggle(150)
      return

  addElementToView: (mediaData) ->
    mediaModel = new GalleryModel
    mediaModel.set mediaData
    new GalleryView(@addOption(
      media: mediaModel
      domContainer: this.$el.find('.superbox')
    ))
    return

  clickAdd: (event) ->
    event.preventDefault()
    viewContext = @
    if $('#main .' + $(event.target).attr('class')).length
      displayLoader('div[role="container"]')
      Backbone.history.navigate('/add')
      $.ajax
        url: @options.medias.get('links')._self_add
        method: 'GET'
        success: (response) ->
          new FullPageFormView(viewContext.addOption(
            html: response
          ))

  addConfigurationButton: ->
    if @options.medias.get('links')._self_folder != undefined
      new FolderConfigurationButtonView(@options)

  addDeleteButton: ->
    if @options.medias.get('is_folder_deletable')
      if @options.medias.get('links')._self_delete != undefined
        view = new FolderDeleteButtonView(@options)
)
