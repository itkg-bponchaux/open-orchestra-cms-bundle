mediaFormView = Backbone.View.extend(
  initialize: (options) ->
    @el = options.el
    @menuUrl = options.menuUrl
    @method = if options.method then options.method else 'GET'
    @call()
    return
  call: ->
    viewContext = this
    displayLoader(@el + ' .modal-body-menu')
    $.ajax
      url: @menuUrl
      method: @method
      success: (response) ->
        nbUserName = response.indexOf "_username"
        nbPassword = response.indexOf "_password"
        if nbUserName > 0 && nbPassword > 0
          Backbone.history.navigate('#', true);
          window.location.reload()
        else
          viewContext.render(
            html: response
          )
      error: ->
        $(@el + ' .modal-body').html 'Erreur durant le chargement'
    return
  render: (options) ->
    $(@el + ' .modal-body-menu').html options.html
)
