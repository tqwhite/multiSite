// steal model files
steal("jquery/model")
.then('./base.js')
.then('./session.js', './local_storage.js')
.then('./account.js')