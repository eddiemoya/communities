/*
* Mint: Ajax loading of comments
* @author Jason Corradino
*
* @require jQuery 1.7
*
* Releases:
*
* @version [1.0: 6/27/13]
* - Initial script release
*
* @version [1.2 8/7/13]
* - Mint rewritten to make entire comment area a clickable area, reducing watchers on the page from several per comment to one per page
*/

shcJSL.methods.mint = function(element) {

    /*
     * [PRIVATE VARIABLES]
     * 
     * self (Object)
     * ----
     * This is the object.
     * 
     */
    var self = this;                    // This object
    
    self.working = false;               // Watcher to make sure multiple ajax calls are not triggered off the same element
    self.element = element;             // Easier to work with if it is stuffed in the self object
    self.ops = "";                      // options of clicked event
    
    /*
     * [PUBLIC METHODS]
     */

    /*
     * Prepare
     * -----------
     * Method that routes all clicks within comment container to their proper action
     *
     * Params:
     *      clicked [object] - clicked element 
     */
    this.prepare = function(clicked) {
        if( $(clicked).attr("shc:options") != undefined) {
            self.ops = eval('(' + $(clicked).attr("shc:options") + ')');

            if (self.ops.comment_parent != undefined || self.ops.post != undefined) {
                $(clicked).html("Loading... <img src='" + ajaxdata.template_dir_uri + "/assets/img/comment-ajax-loader.gif' />");
                self.ops.action = "load_more_comments";
                self.fetchComments(clicked);
            } else if (self.ops.actions.post.name != undefined) {
                self.ops = self.ops.actions.post;
                self.ops.blocker = true;
                shcJSL.methods.voter(clicked, self.ops);
            }
        }
    }

    /*
     * fetchComments
     * -----------
     * Fetch data from server and return result
     *
     * Params:
     *      clicked [object] - clicked element 
     */
    this.fetchComments = function(clicked) {
        $.ajax({
            type: "GET",
            url: ajaxdata.ajaxurl,
            data: self.ops
        }).done(function(returned) {
            self.inject(returned, clicked);
        });
    }

    /*
     * inject
     * -----------
     * Fetch data from server and return result
     *
     * Params:
     *      data [string] - data returned from the server
     *      clicked [object] - clicked element 
     */
    this.inject = function(data, clicked) {
        if (self.ops.comment_parent != undefined) {
            $(clicked).slideUp("fast");
            $(clicked).parent(".comment").after(data);
        } else if (self.ops.post != undefined) {
            $(clicked).parent("li.comment").slideUp("fast");
            $(clicked).closest("ol#allComments").append(data);
        }
    }
    

    $(element).live("click", function(event){
        event.preventDefault();
        self.prepare(event.target);
    });

}

shcJSL.methods.voter = function(element, ops) {
    var self = this;                    // This object
    
    self.working = false;               // Watcher to make sure multiple ajax calls are not triggered off the same element
    self.element = element;             // Easier to work with if it is stuffed in the self object
    self.ops = ops;                     // options of clicked event

    this.vote = function(data) {
        console.log(data);
        $.ajax({
           type: "POST",
           url: ajaxdata.ajaxurl+"?action=add_user_action",
           data: data
        }).done(function(returned) {
          return returned;
        });
    }

    this.planEvent = function() {
        
        var data = self.ops;
        var actionCookie = shcJSL.cookies('actions').serve('value');
        var returned = false;

        if (actionCookie != "") {
            existingCookie = eval('(' + shcJSL.cookies('actions').serve('value') + ')');
            for(var i = 0; i < existingCookie.actions.length; i++) {
                var action = existingCookie.actions[i];
                if (self.ops.id == action.id) {
                    if (action.name == "upvote" && self.ops.name == "upvote") {console.log("upvote-upvote");
                        data.nli_reset = "deactivate";
                        returned = self.vote(data);
                    } else if (action.name == "upvote" && self.ops.name == "downvote") {console.log("upvote-downvote");
                        data.blocker = true;
                        returned = self.vote(data);
                        blocker = null;
                        data.name = "upvote";
                        data.nli_reset = "deactivate";
                        self.vote(data);
                    } else if (action.name == "downvote" && self.ops.name == "upvote") {console.log("downvote-upvote");
                        returned = self.vote(data);
                        data.name = "downvote";
                        data.nli_reset = "deactivate";
                        returned = self.vote(data);
                    } else if (action.name == "downvote" && self.ops.name == "downvote") {console.log("downvote-downvote");
                        data.nli_reset = "deactivate";
                        returned = self.vote(data);
                    }
                }
            }
            if (returned == false) {
                returned = self.vote(data);
            }
        } else {
            returned = self.vote(data);
        }
        self.registerEvent(returned);
    }

    this.registerEvent = function(data) {
        var addedToCookie = false;
        var currentCookie = {}
        var existingCookies = [];
        var jsonString = '{"actions": [';

        if(typeof(shcJSL.cookies('actions').serve('value')) !== 'undefined' && shcJSL.cookies('actions').serve('value') !== '') {
            existingCookies = eval('(' + shcJSL.cookies('actions').serve('value') + ')');
            existingCookies = existingCookies.actions;

            for(var i = 0; i < existingCookies.length; i++) {
                currentCookie = existingCookies[i];

                if(currentCookie.id != self.ops.id) {
                    jsonString += '{"id": "' + currentCookie.id +
                                        '", "name": "' + currentCookie.name +
                                        '", "sub_type": "' + currentCookie.sub_type +
                                        '", "type": "' + currentCookie.type + '"},';

                    addedToCookie = true;
                } else {
                    /**
                     * Is the cookie an upvote? Is the current action a downvote? Turn off the upvote
                     */
                    if(currentCookie.name == 'upvote' && self.ops.name == 'downvote') {
                        continue;
                    } else if(currentCookie.name == 'downvote' && self.ops.name == 'upvote') {
                        continue;
                    } else {
                        jsonString += '{"id": "' + currentCookie.id +
                                            '", "name": "' + currentCookie.name +
                                            '", "sub_type": "' + currentCookie.sub_type +
                                            '", "type": "' + currentCookie.type + '"},';

                        addedToCookie = true;
                    }
                }
            }
        }

        /**
         * If the action is an upvote automatically add it to the cookie list;
         * If the action is a downvote, do not add it to the cookie list;
         */
        if(data !== 'deactivated-out') {
            jsonString += '{"id":"' + self.ops.id + '","name":"' + self.ops.name + '","sub_type":"' + self.ops.sub_type + '","type": "' + self.ops.type + '"}]}';
        } else if(addedToCookie === true) {
            jsonString = jsonString.substring(0, jsonString .length - 1) + "]}";
        } else {
            /**
             * This is to ensure that, if there are no actions at all, to have an empty string.
             */
            jsonString += ']}';
        }

        shcJSL.cookies("actions").bake({value: jsonString, expiration: '1y'});
    };

    if (self.ops != undefined) {
        self.planEvent();
    }
}

shcJSL.gizmos.bulletin['shcJSL.mint.js'] = true;

if (shcJSL && shcJSL.gizmos) {
    shcJSL.gizmos.mint = function(element) {
        shcJSL.get(element).mint();
    }

    shcJSL.gizmos.voter = function(element) {
        shcJSL.get(element).voter();
    }
}