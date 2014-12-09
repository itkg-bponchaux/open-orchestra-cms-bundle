$(document).on 'click', "button.ajax-delete", (e) ->
  e.preventDefault()
  url = $(this).data("delete-url")
  confirm_text = $(this).data("confirm-text")
  redirectUrl = $(this).data('redirect-url')
  $("#OrchestraBOModal").modal "hide"
  smartConfirm(
    titleWhite: 'Delete'
    titleColorized: 'this element'
    text: confirm_text
    yesCallbackParams:
      url: url
    yesCallback: (params) ->
      $.ajax
        url: params.url
        method: 'DELETE'
        success: (response) ->
          if redirectUrl != undefined
            displayMenu(redirectUrl)
          else
            Backbone.history.history.back()
            displayMenu(Backbone.history.fragment)
          return
        error: (response) ->
          $('.modal-footer', this.el).html response.responseJSON.error.message
          return
    noCallback: ->
      $("#OrchestraBOModal").modal "show"
  )
