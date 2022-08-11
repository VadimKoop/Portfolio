var Stylus = {
  //setup before functions
  searchTerm: "",
  typingTimer: 0, // timer identifier
  doneTypingInterval: 2000, // time in ms, 2 second for example
  accordion: ".accordion",
  accordionHeader: ".accordion .accordion__item .accordion__header",

  setSearchAttr: function () {
    /* Add 'search_attr' to accordion headers */
    jQuery(this.accordionHeader).each(function () {
      var search_attr = jQuery(this)
        .text()
        .toLowerCase();

      if (
        jQuery(this)
          .closest(".accordion")
          .closest(".accordion__body")
          .closest(".accordion__item").length > 0
      ) {
        search_attr +=
          " " +
          jQuery(this)
            .closest(".accordion")
            .closest(".accordion__body")
            .closest(".accordion__item")
            .find(".accordion__header")
            .first()
            .text()
            .toLowerCase();
      }

      jQuery(this).attr("data-search-term", search_attr);
    });

    /* Add 'search_attr' to accordion section title ( category name ) */
    jQuery(".helpie-faq.accordions .accordion__section h3").each(function () {
      var search_attr = jQuery(this)
        .text()
        .toLowerCase();
      jQuery(this).attr("data-search-term", search_attr);
    });
  },

  isContentMatch: function (element, searchTerm) {
    var content = jQuery(element)
      .closest(".accordion__item")
      .find(".accordion__body")
      .text()
      .toLowerCase();
    // $is_match = content.match(searchTerm);
    if (content.indexOf(searchTerm) >= 0) return true;

    return false;
    // return $is_match;
  },

  isTitleMatch: function (element, searchTerm) {
    var filterMatch =
      jQuery(element).filter("[data-search-term *= " + searchTerm + "]")
        .length > 0;
    var TitleMatch = filterMatch || searchTerm.length < 1 ? true : false;

    return TitleMatch;
  },

  onSearchKeyup: function (that) {
    var thisModule = this;
    const searchTerm = jQuery(that)
      .val()
      .toLowerCase();
    thisModule.searchTerm = jQuery(that)
      .val()
      .toLowerCase();

    /* Loop through each accordion item header */
    jQuery(that)
      .closest(".helpie-faq.accordions")
      .find(thisModule.accordionHeader)
      .each(function () {
        var TitleMatch = thisModule.isTitleMatch(this, searchTerm);
        var ContentMatch = thisModule.isContentMatch(this, searchTerm);

        var show = TitleMatch || ContentMatch;
        if (show == true) {
          jQuery(this)
            .closest(".accordion__item")
            .show();

          var accordionItem = jQuery(this)
            .closest(".accordion__item")
            .closest(".accordion__body")
            .closest(".accordion__item");
          if (accordionItem.length > 0) {
            accordionItem.show();
          }
        } else {
          jQuery(this)
            .closest(".accordion__item")
            .hide();
        }
      });


    /**
     * When "Simple Accordion by Category" display mode is used, the search filter should bring 
     * only the particular faq searched for and the category title it belongs to. 
     * It should not bring the category titles of FAQ's not searched for.
     */
  
     jQuery(that)
      .closest(".helpie-faq.accordions")
      .find(thisModule.accordion)
      .each(function(){
        
        var totalItems = jQuery(this).find('.accordion__item').length;
        var hiddenItemsCount = 0;

        jQuery(this)
          .find('.accordion__item')
          .each(function(){
            if(jQuery(this).css('display') === 'none'){
              hiddenItemsCount = hiddenItemsCount + 1;
            }
          });
        
        if(totalItems == hiddenItemsCount){
          jQuery(this).hide();
          jQuery(this).prev('h3').hide();
        }else {
          jQuery(this).show();
          jQuery(this).prev('h3').show();
        }
    });

    /* Hide Category Title ( h3 ) */

    if (searchTerm.length < 1) {
      jQuery(that)
        .closest(".helpie-faq.accordions")
        .find(".accordion__section")
        .show();
      return;
    }

    if (
      jQuery(that)
        .closest(".helpie-faq.accordions")
        .has(".accordion__section")
    ) {
      jQuery(that)
        .closest(".helpie-faq.accordions")
        .find(".accordion")
        .each(function () {
          var showOne = false;

          jQuery(this)
            .find(".accordion__item")
            .each(function () {
              var TitleMatch = thisModule.isTitleMatch(this, searchTerm);
              var ContentMatch = thisModule.isContentMatch(this, searchTerm);

              var show = TitleMatch || ContentMatch;
              showOne = showOne || show;
            });

          if (showOne == false) {
            jQuery(this)
              .closest(".accordion__section")
              .hide();
          } else {
            jQuery(this)
              .closest(".accordion__section")
              .show();
          }

          /* Show entire Matched category */
          var filterMatch =
            jQuery(this)
              .closest(".accordion__section")
              .find("h3")
              .filter('[data-search-term *= "' + searchTerm + '"]').length > 0;
          var showCategory =
            filterMatch || searchTerm.length < 1 ? true : false;

          if (showCategory == true) {
            jQuery(this)
              .closest(".accordion__section")
              .show();
            jQuery(this)
              .closest(".accordion__section")
              .find(".accordion .accordion__item")
              .show();
          }
        });

      return searchTerm;
    }
  },

  onSearchKeydown: function (that) {
    // console.log('KEYDOWN: ');
    const ENTER = 13;

    if (e.keyCode == ENTER) {
      e.preventDefault();
      e.stopPropagation();

      const toAdd = jQuery("input[class=live-search-box]").val();
      if (toAdd) {
        jQuery("<li/>", {
          text: toAdd,
          "data-search-term": toAdd.toLowerCase()
        }).appendTo(jQuery("ul"));
        jQuery("input[class=live-search-box]").val("");
      }
    }
  },

  init: function () {
    var thisModule = this;
    console.log("Search init");
    thisModule.setSearchAttr();
    jQuery("body").on("keyup", ".live-search-box", function () {
      var searchTerm = thisModule.onSearchKeyup(this);
      console.log("searchTerm: " + searchTerm);

      // For ajaxCall, start the countdown

      clearTimeout(thisModule.typingTimer);
      thisModule.typingTimer = setTimeout(function () {
        // donetyping() method
        // TODO: Move to Tracker.js or create a child
        /* <fs_premium_only> */
        var data = {
          action: "helpie_faq_search_counter",
          nonce: thisModule.nonce,
          searchTerm: searchTerm
        };

        jQuery.post(my_faq_ajax_object.ajax_url, data, function (response) {
          // console.log(response);
        });
      }, thisModule.doneTypingInterval);
    });

    jQuery("input[class=live-search-box]").keydown(function (e) {
      thisModule.onSearchKeydown(this);

      // For ajaxCall, clear the countdown
      clearTimeout(thisModule.typingTimer);
    });
  }
};

module.exports = Stylus;
