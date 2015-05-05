adminFormView = OrchestraView.extend(
  el: '#OrchestraBOModal'

  initialize: (options) ->
    @options = @reduceOption(options, [
      'deleteUrl'
      'confirmText'
      'confirmTitle'
      'redirectUrl'
      'url'
    ])
    @deleteButton = @options.deleteUrl && @options.confirmText && @options.confirmTitle
    @method = if options.method then options.method else 'GET'
    @events = @events || {}
    @formEvent = 'submit'
    @formClass = 'form'
    @loadTemplates [
        'OpenOrchestraBackofficeBundle:BackOffice:Underscore/deleteButton'
    ]
    $('.modal-footer', @el).addClass("hidden-info")
    return

  render: ->
    viewContext = this
    displayLoader('.modal-body')
    $("#OrchestraBOModal").modal "show"
    $.ajax
      url: @options.url
      method: @method
      success: (response) ->
        viewContext.renderContent(
          html: response
        )
      error: ->
        $('.modal-body', viewContext.el).html 'Erreur durant le chargement'
    return

  renderContent: (options) ->
    $('.modal-body', @el).html options.html
    $('.modal-title', @el).html $('#dynamic-modal-title').html()
    if @deleteButton && $('form.form-disabled', @el).length == 0
      $('.modal-footer', @el).html @renderTemplate('OpenOrchestraBackofficeBundle:BackOffice:Underscore/deleteButton', @options)
      $('.modal-footer', @el).removeClass("hidden-info")
      $('.modal-footer', @el).prepend($('.submit_form', @$el))
      @formEvent = 'click'
      @formClass = '.submit_form'
    $("[data-prototype]", @$el).each ->
      PO.formPrototypes.addPrototype $(this)
      return
    @addEventOnSave()
    Backbone.Wreqr.radio.commands.execute 'widget', 'loaded', @$el

  addEventOnSave: ->
    viewContext = this
    $(@formClass, @$el).on @formEvent, (e) ->
      e.preventDefault() # prevent native submit
      $("form", viewContext.$el).ajaxSubmit
        context:
          button: $(".submit_form", e.target.parentNode).parent()
        statusCode:
          200: (response) ->
            view = viewContext.renderContent(
              html: response
            )
            if $('#node_nodeId', viewContext.$el).length > 0
              displayRoute = appRouter.generateUrl "showNode",
                nodeId: $('#node_nodeId', viewContext.$el).val()
            else if $('#template_templateId', viewContext.$el).length > 0
              displayRoute = appRouter.generateUrl "showTemplate",
                templateId: $('#template_templateId', viewContext.$el).val()
            else
              displayRoute = Backbone.history.fragment
              Backbone.history.loadUrl(displayRoute)
            displayMenu(displayRoute)
          400: (response) ->
            view = viewContext.renderContent(
              html: response.responseText
            )
    return
)
