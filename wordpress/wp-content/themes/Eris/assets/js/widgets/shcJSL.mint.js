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
*/

/* 
 * mint.g 
 * ----
 * (Object)
 * This is the global object for the site scipts.
 * 
 * mint.p
 * ----
 * (String)
 * Prefix used on the name spaces for attributes.
 */
var mint = {};
mint.g = shcJSL;
mint.p = 'shc';

//var mint;

/*
 * Arguments
  
   element: (HTMLObject), Required
     Comment link tlement
     
   options: (Object), Optional
     Comment options
  
 */

mint = function(element, options) {
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
    self.element = element              // Easier to work with if it is stuffed in the self object
    
    /*
     * [PUBLIC METHODS]
     * 
     * Prepare Options
     * -----------
     * Fetch options attached to clicked element
     *
     */
    this.prepareOptions = function() {
        var options = $(self.element).attr("shc:options");
        if (typeof options == "string") options = eval("(" + options + ")")
        self.conf = $.extend({},{
            commParent: undefined,
            offset: undefined,
            commPage: undefined,
            commPost: undefined,
        }, options);
    }
    
    /* 
     * Fetch
     * -----------
     * Fetch content from server
     *
     */
    this.fetch = function(data) {
        $.ajax({
            type: "GET",
            url: ajaxdata.ajaxurl,
            data: data
        }).done(function(returned) {
            if (self.conf.commPage != undefined) self.appendPage(returned);
            if (self.conf.commParent != undefined) self.appendParent(returned);
        });
    }
    
    /* 
     * Prepare
     * -----------
     * Prepare params to request from the server
     *
     */
    this.prepare = function() {
        var data = {};
        
        data.action = "load_more_comments";
        if (self.conf.commParent != undefined) data.comment_parent = self.conf.commParent;
        if (self.conf.offset != undefined) data.comment_offset = self.conf.offset;
        if (self.conf.commPage != undefined) data.page = self.conf.commPage;
        if (self.conf.commPost != undefined) data.post = self.conf.commPost;
        return data;
    }
    
    /* 
     * Append Page
     * -----------
     * Append next page of comments to the end of the comment list
     *
     */
    this.appendPage = function(data) {
        $(self.element).parent(".comment").slideUp("fast");
        $(self.element).closest("ol#allComments").append(data);
        $.each(
            $(".moreComments"), function(index, value) {
                mint(this);
            }
        );
        working = false;
    }
    
    /* 
     * Append Parent
     * -----------
     * Append comments to a parent element
     *
     */
    this.appendParent = function(data) {
        $(self.element).slideUp("fast");
        $(self.element).parent(".comment").after(data);
        $.each(
            $(".moreComments"), function(index, value) {
                mint(this);
            }
        );
        working = false;
    }
    
    $(element).live("click", function(event){
        event.preventDefault();
        self.element = element;
        self.prepareOptions();
        $(this).html("<span>Loading...</span>");
        if (working == false) {
            working = true;
            var data = self.prepare();
            self.fetch(data);
        }
    })
}

if(shcJSL && shcJSL.gizmos) {
    shcJSL.gizmos.mint = function(element) {
        $.each(
            $(".moreComments"), function(index, value) {
                mint(this);
            }
        );
    }
}