// load('pinpoint/scripts/crawl.js')

load('steal/rhino/rhino.js')

steal('steal/html/crawl', function(){
  steal.html.crawl("pinpoint/pinpoint.html","pinpoint/out")
});
