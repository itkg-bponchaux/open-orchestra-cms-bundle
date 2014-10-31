$("button.ajax-delete").click (e) ->
  e.preventDefault()
  url = $(this).data("delete-url")
  confirm_text = $(this).data("confirm-text")
  confirm_text = "Are you sure to delete this?"
  if confirm(confirm_text)
    $.ajax
      type: "DELETE"
      url: url
      success: (response) ->
        Backbone.history.navigate "#", true
        window.location.reload()
        return
      error: ->
        $('.modal-footer', this.el).html 'Erreur vous ne pouvez pas supprimé ce répertoire'
        return
    return
  return
