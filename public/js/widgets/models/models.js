// steal model files
steal("jquery/model")
.then('./base.js')
.then('./local_storage.js', './session.js', './purchase.js', './utility.js')
.then('./simple_data.js', './email.js', './browser.js', './html_data.js')