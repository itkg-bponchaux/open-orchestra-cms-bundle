extendView = extendView || {}
extendView['contentTypeSelector'] = {
  events:
    'change .contentTypeSelector': 'changeContentTypeSelector'

  changeContentTypeSelector: (event) ->
    event.preventDefault()
    target = $(event.currentTarget)
    $.ajax
      type: "GET"
      url: target.data("url")
      data:
        content_type: target.val()
      success: (response) ->
        $("#block_contentId").find("option").remove()
        $.each response.contents, (index, item) ->
          $("#block_contentId").append new Option(item["name"], item["id"])
          return
        return
    return
}
