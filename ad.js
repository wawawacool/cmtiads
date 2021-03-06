 (function(window) {
    document._write = document.write;
    undefined = "undefined";
    var isie = false;
    if (navigator.appName == "Microsoft Internet Explorer") {
        isie = true;
        var onload = function(func,unique) {
            document._write('<script type="text/javascript" ' 
                         + 'id="loading_ie_fallback'
                         + unique
                         + '" defer="defer" '
                         + 'src="javascript:void(0)">'
                         + '<\/script>');

            var cload = document.getElementById("loading_ie_fallback"+unique);
            cload.onreadystatechange = function() {
                if( this.readyState=="complete" ) {
                    func();
                }
            };
        };
    }
    var registr = {
        scriptcont:/<script.*?\/script>/gi,
        jsurl:/src\s*=\s*[\'\"]\s*http[s]*:\/\/.*\.js/gi,
        scripttags:/<[\/]*script[^>]*>/gi
    };
    var workmedcont = function workmedcont(medcont,target) {
        medcont = medcont.replace(/[\r\n\t]/g,"");
        var scripts = medcont.match(registr.scriptcont,"") || [],
            nodes = [];
        medcont = medcont.replace(/&#rn;/g,"\r\n");
        medcont = medcont.replace(/&#n;/g,"\n");
        medcont = medcont.replace(registr.scriptcont,"");   

        for( iter=0;iter<scripts.length;iter++ ) {

            var script = scripts[iter],
                opentag = script.match(/^<[^>]+/)[0],
                attributes = opentag.match(/[a-zA-Z]+\s*=\s*[\'\"][^\'\"]+/gi),
                pass = {};
            script = script.replace(/<\!--.*?\*\//,"");
            script = script.replace(/\/\*.*?\/-->/,"");
            if( attributes ) {
                var set = [];
                for( _iter=0;_iter<attributes.length;_iter++ ) {
                    var set = attributes[_iter].replace(/[\'\"\s]/g,'')
                                              .split("=");
                    var attr_name = set.shift();
                    var attr_val = set.join("=");
                    pass[attr_name] = attr_val;
                }
            }
            nodes[nodes.length] = [document.createElement("script"),false];
            
            for( var attr in pass ) {
                nodes[nodes.length-1][0][attr] = pass[attr];
            }

            if( !pass["src"] ) {
                script = script.replace(registr.scripttags,"");
                nodes[nodes.length] = [script,true];
            }
        }
        target.innerHTML = target.innerHTML + medcont;

        document.write = function(append) { 
            workmedcont(append,target);
        }

        for( iter=0;iter<nodes.length;iter++ ) {
            if( nodes[iter][1] ) {
                try {
                    window.eval(nodes[iter][0]);
                } 
                catch(e) {}
            } else {
                target.appendChild(nodes[iter][0]);
            }
        }
    }

    var create = function() {
        
        var adcount = 0;

        return function(cmtiadsvariables) {

            adcount++;
            var id = 'global_ad_id' + adcount;
            
            if( !cmtiadsvariables.s ) return;

            var src = cmtiadsvariables.requesturl;
            if( typeof cmtiadsvariables.service != "undefined" ) {
                src = cmtiadsvariables.service;
            }

            var cgi = ['p=' + escape(document.location),
                       'random=' + Math.random(),
                       'rt=javascript',
                       'v=js_10',
                       'jsvar=' + id,
                       'u=' + navigator.userAgent];

            for( var setting in cmtiadsvariables ) {
                var cgivar = setting + '=' + escape(cmtiadsvariables[setting]);
                cgi[cgi.length] = cgivar;
            }
            src += "?" + cgi.join('&');

            document._write("<div id='" + id + "'></div>");
            var container = document.getElementById(id),
                varscall = document.createElement("script");

            if( cmtiadsvariables.backfillhtml ) {
                container.innerHTML = "<div id= 'm_dcontent" 
                                    + id + "' "
                                    + "style='display:none'>"
                                    + cmtiadsvariables.backfillhtml
                                    + "</div>";
            }

            varscall.type = "text/javascript";
            var varsloaded = function() {

                
                if( isie ) {
                    container = document.getElementById(id);
                }

                var ads = window[id];

                for( iter=0;iter<ads.length;++iter ) {

                    var ad = ads[iter];
                    if( !cmtiadsvariables.target ) {
                        cmtiadsvariables.target = '';
                    }

                    if( ad.error ) {
                        if( cmtiadsvariables.backfillhtml && cmtiadsvariables.reveal ) {
                            container.innerHTML = ad.error;
                        }
                        continue;  
                    }

                    if( ad.img ) {
                        var width = (cmtiadsvariables.img_width ? cmtiadsvariables.img_width: ''),
                            height = (cmtiadsvariables.img_height ? cmtiadsvariables.img_height: ''),
                            tag = "<a href='" 
                                + ad.url 
                                + "' target='" + cmtiadsvariables.target + "'>"
                                + "<img src='" + ad.img + "'"; 

                        if( cmtiadsvariables.img_width ) {
                            tag += " width='" + width + "'";
                        }         
                        if( cmtiadsvariables.img_height ) {
                            tag += " height='" + height + "'";
                        }         

                        tag += "/></a>"; 

                        container.innerHTML = tag;
                    } else if( ad.content ) {
                        container.innerHTML = '';
                        workmedcont(ad.content,container);
                    } else {
                        var tag = "<a href='" + ad.url + "' "
                                + "target='" + cmtiadsvariables.target + "'>"
                                + ad.text
                                + "</a>";

                        container.innerHTML = tag;
                    }

                    var track = new Image();
                    track.src = ad.track;
                }

                if( dcont = document.getElementById("m_dcontent" + id) ) {
                    dcont.style.display = "block";
                }

                if( cmtiadsvariables.prependclickcontent ) {
                    var links = container.getElementsByTagName("a");
                    for( iter=0;iter<links.length;++iter ) {
                        var link = links[iter],
                            original = link.href;
                        link.href = cmtiadsvariables.prependclickcontent + original;
                    }
                }
             
                if( cmtiadsvariables.trackingpixelurl ) {
                    var cachebust = Math.random(),
                        join = (cmtiadsvariables.trackingpixelurl.indexOf('?') ? '&' : '?'),
                        img = new Image();

                    img.src = cmtiadsvariables.trackingpixelurl + join + cachebust; 
                }
                if( !isie ) delete this;
            };

            if( !isie ) {
                varscall.onload = varsloaded;
            } else {
                varscall.onreadystatechange = function() {
                    if( this.readyState != 'complete'
                     && this.readyState != 'loaded') return;

                    varsloaded();
                    this.onreadystatechange = null;
                }
            }
            varscall.src = src;
            if( !isie ) {
                window.addEventListener(
                    "DOMContentLoaded",
                    function() {
                        container.appendChild(varscall);
                    },
                    true
                );
            } else {
                onload(function() {  
                    document.getElementById(id).appendChild(varscall); 
                },id);
            }
            if( !isie ) delete this;
        };
    };
    if( !window.RequestAd_ ) {
        window.RequestAd_ = create(); 
    }
})(window);