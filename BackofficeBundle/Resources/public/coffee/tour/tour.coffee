widgetChannel.bind 'ready', (view) ->
   if ($(view.el).find('.js-widget-blockpanel').length > 0)
       tour = new Tour(
           debug: true,
           storage: false,
           delay: 20
           steps: [
             {
               element: '.js-widget-blockpanel .header'
               title: 'Title of my step'
               content: 'Content of my step'
               placement: "left"
               reflex: true
             }
             {
               element: 'div.panel-block[data-block-type="tiny_mce_wysiwyg"][data-node-id!="transverse"]'
               title: 'Title of my step'
               content: 'Content of my step'
               placement: "left"
               mousedown : () ->
                    tour.next()
               onShow: (tour) ->
                  $(this.element).bind 'mousedown', @mousedown
                  console.log $(this.element)
               onHide: (tour) ->
                  $(this.element).unbind 'mousedown', @mousedown
             }
             {
               element: '.bo-column.ui-sortable:eq(1)'
               title: 'Title of my step'
               content: 'Content of my step'
               placement: "top"
               mouseup : () ->
                    console.log "mouse up"
               onShow: (tour) ->
                  $(this.element).bind 'mouseup', @mouseup
                  $(this.element).bind 'drop', @mouseup
                  $(this.element).bind 'sortstop', @mouseup
                  console.log $(this.element)
               onHide: (tour) ->
                  $(this.element).unbind 'mouseup', @mouseup
                  $(this.element).unbind 'drop', @mouseup
                  $(this.element).unbind 'sortstop', @mouseup
             }
           ]
       )
       tour.init()
       tour.start()