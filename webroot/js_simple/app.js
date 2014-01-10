define([
       "jquery", "underscore" , "backbone"
       , "collections/snippets" , "collections/my-form-snippets"
       , "views/tab" , "views/my-form"
       , "text!data/input.json", "text!data/radio.json", "text!data/select.json", "text!data/buttons.json"
       , "text!templates/app/render.html",  "text!templates/app/about.html", "answers",
], function(
  $, _, Backbone
  , SnippetsCollection, MyFormSnippetsCollection
  , TabView, MyFormView
  , inputJSON, radioJSON, selectJSON, buttonsJSON
  , renderTab, aboutTab
){
  return {
    initialize: function(){
      //Bootstrap tabs from json.
      new TabView({
        title: "Input"
        , collection: new SnippetsCollection(JSON.parse(inputJSON))
      });
      new TabView({
        title: "Radios / Checkboxes"
        , collection: new SnippetsCollection(JSON.parse(radioJSON))
      });
      new TabView({
        title: "Select"
        , collection: new SnippetsCollection(JSON.parse(selectJSON))
      });
      // new TabView({
        // title: "Buttons"
        // , collection: new SnippetsCollection(JSON.parse(buttonsJSON))
      // });
      // new TabView({
        // title: "Rendered"
        // , content: renderTab
      // });
      // new TabView({
        // title: "About"
        // , content: aboutTab
      // });
      
      //Make the first tab active!
      $("#formBuilder .tab-pane").first().addClass("active");
      $("#formBuilder ul.nav li").first().addClass("active");
      
      //This gets the data from the hidden field populated from our database
      rendered = $('#renderJson').val();
      if(rendered !== '') {
      	rendered = JSON.parse(rendered);
      }
      
      // Bootstrap "My Form" with 'Form Name' snippet.
      new MyFormView({
        title: "Original"
        , collection: new MyFormSnippetsCollection(rendered)
      });
    }
  }
});
