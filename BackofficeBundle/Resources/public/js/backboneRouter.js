var OrchestraBORouter = Backbone.Router.extend({

  // Contains currentMainView, usefull to unbind event when view is changed
  currentMainView: null,
  
  // Declare here only routes that are not declared in this.routes.
  // Routes in this.routes will be automatically added to routePatterns at init time
  // cf this.generateRoutePatterns()
  routePatterns: {
    'loadUndescroreTemplate': '/app_dev.php/admin/underscore-template/show/:templateId'
  }, 

  //========[ROUTES LIST]===============================//

  routes: {
    'node/show/:nodeId/:language/:version': 'showNodeWithLanguageAndVersion',
    'node/show/:nodeId/:language': 'showNodeWithLanguage',
    'node/show/:nodeId': 'showNode',
    'template/show/:templateId': 'showTemplate',
    'contents/list/:contentTypeId': 'listContents',
    'websites/list': 'listSites',
    'themes/list': 'listThemes',
    'status/list': 'listStatus',
    'user/list': 'listUser',
    'role/list': 'listRole',
    'content-types/list': 'listContentTypes',
    ':folderId/list': 'listFolder',
    ':list/list/edit': 'redirectToList',
    'translation': 'listTranslations',
    '': 'showHome'
  },

  initialize: function() {
    this.generateRoutePatterns();
  },

//========[ACTIONS LIST]==============================//

  showHome: function()
  {
    drawBreadCrumb();
  },

  showNode: function(nodeId)
  {
    this.initDisplayRouteChanges();
    showNode($("#nav-node-" + nodeId).data("url"));
  },

  showNodeWithLanguageAndVersion: function(nodeId, language, version)
  {
    this.initDisplayRouteChanges("#nav-node-" + nodeId);
    showNode($("#nav-node-" + nodeId).data("url"), language, version);
  },

  showNodeWithLanguage: function(nodeId, language)
  {
    this.initDisplayRouteChanges("#nav-node-" + nodeId);
    showNode($("#nav-node-" + nodeId).data("url"), language);
  },

  showTemplate: function(templateId)
  {
    this.initDisplayRouteChanges();
    showTemplate($("#nav-template-" + templateId).data("url"));
  },

  listFolder: function(folderId)
  {
    this.initDisplayRouteChanges();
    GalleryLoad($('#' + folderId));
  },

  listContents: function(contentTypeId)
  {
    this.initDisplayRouteChanges();
    tableViewLoad($("#nav-contents-" + contentTypeId));
  },

  listSites: function()
  {
    this.initDisplayRouteChanges();
    tableViewLoad($("#nav-websites"));
  },

  listThemes: function()
  {
    this.initDisplayRouteChanges();
    tableViewLoad($("#nav-themes"));
  },

  listStatus: function()
  {
    this.initDisplayRouteChanges();
    tableViewLoad($("#nav-status"));
  },

  listUser: function()
  {
    this.initDisplayRouteChanges();
    tableViewLoad($("#nav-user"));
  },

  listRole: function()
  {
    this.initDisplayRouteChanges();
    tableViewLoad($("#nav-role"));
  },

  listContentTypes: function()
  {
    this.initDisplayRouteChanges();
    tableViewLoad($("#nav-contentTypes"));
  },

  createTemplate: function()
  {
    var templateRoot = $("#nav-createTemplate");
    this.showNodeForm(templateRoot);
  },

  listTranslations: function()
  {
    drawBreadCrumb();
    var view = new TranslationView(
      {url : $("#nav-translation").data("url")}
    );
    this.setCurrentMainView(view)
    return view;
  },

  redirectToList: function(list)
  {
    Backbone.history.navigate(list + '/list', true);
  },

//========[INTERNAL FUNCTIONS]========================//

  generateRoutePatterns: function()
  {
    var currentRouter = this;
    $.each(this.routes, function(pattern, name) {
      currentRouter.routePatterns[name] = pattern;
    });
  },

  initDisplayRouteChanges: function(selector)
  {
    $('nav li.active').removeClass("active");
      if (selector == undefined) {
          var url = '#' + Backbone.history.fragment;
          $('nav li:has(a[href="' + url + '"])').addClass("active");
          var title = ($('nav a[href="' + url + '"]').attr('title'))
          document.title = (title || document.title);
      } else {
          $('nav li:has(a' + selector + ')').addClass("active");
          var title = ($('nav a' + selector).attr('title'))
          document.title = (title || document.title);
      }


    drawBreadCrumb();

    this.removeCurrentMainView();

    displayLoader();
  },

  showNodeForm: function(parentNode)
  {
    $('.modal-title').text(parentNode.text());
    $.ajax({
      url: parentNode.data('url'),
      method: 'GET',
      success: function(response) {
        $('#OrchestraBOModal').modal('show');
        var view;
        return view = new adminFormView({
          html: response
        });
      }
    }); 
  },

  setCurrentMainView: function(view)
  {
    this.currentMainView = view;
  },

  removeCurrentMainView: function()
  {
    if (this.currentMainView) {
      this.currentMainView.remove();
      this.setCurrentMainView(null);
      $('#main').append('<div id="content" />');
    }
  },

  generateUrl: function(routeName, paramsObject)
  {
    var route = this.routePatterns[routeName];
    
    if (typeof route !== "undefined") {
      $.each(paramsObject, function(paramName, paramValue) {
        route = route.replace(':' + paramName, paramValue);
      });
    } else {
      alert('Error, route name is unknown');
      return false;
    }
    
    return route;
  }
});


var appRouter = new OrchestraBORouter();

if (window.location.pathname.indexOf('login') == -1) {
    Backbone.history.start();
}
