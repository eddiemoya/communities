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
     *      flavor [object] - clicked element 
     */
    this.prepare = function(flavor) {
        if( $(flavor).attr("shc:options") != undefined) {
            self.ops = eval('(' + $(flavor).attr("shc:options") + ')');
            $(flavor).attr("shc:options", "");

            if (self.ops.comment_parent != undefined || self.ops.post != undefined) {
                $(flavor).html("Loading... <img src='" + ajaxdata.template_dir_uri + "/assets/img/comment-ajax-loader.gif' />");
                self.ops.action = "load_more_comments";
                self.make(flavor);
            }
        }
    }

    /*
     * Make
     * -----------
     * Fetch data from server and return result
     *
     * Params:
     *      flavor [object] - clicked element 
     */
    this.make = function(flavor) {
        $.ajax({
            type: "GET",
            url: ajaxdata.ajaxurl,
            data: self.ops
        }).done(function(returned) {
            self.unwrap(returned, flavor);
        });
    }

    /*
     * Unwrap
     * -----------
     * Fetch data from server and return result
     *
     * Params:
     *      data [string] - data returned from the server
     *      flavor [object] - clicked element 
     */
    this.unwrap = function(data, flavor) {
        if (self.ops.comment_parent != undefined) {
            $(flavor).slideUp("fast");
            $(flavor).parent(".comment").after(data);
        } else if (self.ops.post != undefined) {
            $(flavor).parent("li.comment").slideUp("fast");
            $(flavor).closest("ol#allComments").append(data);
        }
    }
    

    $(element).live("click", function(event){
        event.preventDefault();
        self.prepare(event.target);
    });

}

shcJSL.gizmos.bulletin['shcJSL.mint.js'] = true;

if (shcJSL && shcJSL.gizmos) {
    shcJSL.gizmos.mint = function(element) {
        shcJSL.get(element).mint();
    }
}