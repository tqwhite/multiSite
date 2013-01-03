// load('widgets/scripts/crawl.js')

load('steal/rhino/rhino.js')

steal('steal/html/crawl', function(){
  steal.html.crawl("widgets/widgets.html","widgets/out")
});
