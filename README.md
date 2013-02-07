
This site operates on two file directories. One contains the website and the other the content. Typically, they are in the same directory. If so, then the default config.ini has a statement that will make it work.

To get started, you need to find the directory 'configsINI' and duplicate it to become 'configs'. 

You need to make your web server config (I've only ever tried it with apache, but it doesn't make use of anything apache specific) point to the 'public' folder inside of websites. 

	DocumentRoot /home/websites/siteName/multiSite/website/public/

	SetEnv ROOT_DOMAIN_SEGMENT tqwhite.org
	SetEnv APPLICATION_ENV development
	SetEnv SITE_VARIATION main


It also has to have three php directives. The SITE_VARIATION directive has to have the name of a directory inside the 'content' directory, for the example above, it would be 'blah/content/main'.

To get started, you can copy the contents of the 'demoContent/dev' directory into 'main'. 

In principle, once you get apache serving that pubic directory, you should see the content you copied from the 'demoContent/dev' directory.



Once that works, look at routes.ini. It contains page specifications. In particular, note the line that specifies the route. One of the demo items says, 'pureHtml'. If you go to your site: http://domain.com/pureHtml, you will see the contents of that page.

Take a look at each of the different routes. Each refers to a different page type. Check them out.

You can get rid of the demo content whenever you want and you can make new routes that satisfy your needs. As explained in the routes.ini file, each page description block links to a directory in the content directory based on the second section of the descriptor.

Note also that there is a directory named _GLOBAL. It does the obvious thing, contain common elements. Any files you put in the CSS directory will be loaded into the page. The COMPONENTS directory will substitute for any missing elements in your page directory, eg, most pages have a headBanner.html file, if you put it into COMPONENTS, it will be common to any pages from which you remove the file.

There are other things, too. Keep an eye on this demo directory for examples.