(function($) {
    "use strict";

    /* Switch Layout 
  -------------------------------------------------------------------------------------*/
    var swLayoutShop = function() {
        let isListActive = $(".sw-layout-list").hasClass("active");
        let userSelectedLayout = null;

        function hasValidLayout() {
            return (
                $("#gridLayout").hasClass("tf-col-2") ||
                $("#gridLayout").hasClass("tf-col-3") ||
                $("#gridLayout").hasClass("tf-col-4") ||
                $("#gridLayout").hasClass("tf-col-5") ||
                $("#gridLayout").hasClass("tf-col-6") ||
                $("#gridLayout").hasClass("tf-col-7")
            );
        }

        function updateLayoutDisplay() {
            const windowWidth = $(window).width();
            const currentLayout = $("#gridLayout").attr("class");

            if (!hasValidLayout()) {
                console.warn("Page does not contain a valid layout (2-7 columns), skipping layout adjustments.");
                return;
            }

            if (isListActive) {
                $("#gridLayout").hide();
                $("#listLayout").show();
                $(".wrapper-control-shop").addClass("listLayout-wrapper").removeClass("gridLayout-wrapper");
                return;
            }

            if (userSelectedLayout) {
                if (windowWidth <= 767) {
                    setGridLayout("tf-col-2");
                } else if (windowWidth <= 1200 && userSelectedLayout !== "tf-col-2") {
                    setGridLayout("tf-col-3");
                } else if (
                    windowWidth <= 1400 &&
                    (userSelectedLayout === "tf-col-5" || userSelectedLayout === "tf-col-6" || userSelectedLayout === "tf-col-7")
                ) {
                    setGridLayout("tf-col-4");
                } else {
                    setGridLayout(userSelectedLayout);
                }
                return;
            }

            if (windowWidth <= 767) {
                if (!currentLayout.includes("tf-col-2")) {
                    setGridLayout("tf-col-2");
                }
            } else if (windowWidth <= 1200) {
                if (!currentLayout.includes("tf-col-3")) {
                    setGridLayout("tf-col-3");
                }
            } else if (windowWidth <= 1401) {
                if (currentLayout.includes("tf-col-5") || currentLayout.includes("tf-col-6") || currentLayout.includes("tf-col-7")) {
                    setGridLayout("tf-col-4");
                }
            } else {
                $("#listLayout").hide();
                $("#gridLayout").show();
                $(".wrapper-control-shop").addClass("gridLayout-wrapper").removeClass("listLayout-wrapper");
            }
        }

        function setGridLayout(layoutClass) {
            $("#listLayout").hide();
            $("#gridLayout").show().removeClass().addClass(`wrapper-shop tf-grid-layout ${layoutClass}`);
            $(".tf-view-layout-switch").removeClass("active");
            $(`.tf-view-layout-switch[data-value-layout="${layoutClass}"]`).addClass("active");
            $(".wrapper-control-shop").addClass("gridLayout-wrapper").removeClass("listLayout-wrapper");
            isListActive = false;
        }

        $(document).ready(function() {
            if (isListActive) {
                $("#gridLayout").hide();
                $("#listLayout").show();
                $(".wrapper-control-shop").addClass("listLayout-wrapper").removeClass("gridLayout-wrapper");
            } else {
                $("#listLayout").hide();
                $("#gridLayout").show();
                updateLayoutDisplay();
            }
        });

        $(window).on("resize", () => {
            updateLayoutDisplay();

            if ($(".meta-filter-shop").hasClass("active") == false) {
                limitLayout();
            }
        });

        $(".tf-view-layout-switch").on("click", function() {
            const layout = $(this).data("value-layout");
            $(".tf-view-layout-switch").removeClass("active");
            $(this).addClass("active");
            $(".wrapper-control-shop").addClass("loading-shop");
            setTimeout(() => {
                $(".wrapper-control-shop").removeClass("loading-shop");
                if (isListActive) {
                    $("#gridLayout").css("display", "none");
                    $("#listLayout").css("display", "");
                } else {
                    $("#listLayout").css("display", "none");
                    $("#gridLayout").css("display", "");
                    if ($(".meta-filter-shop").hasClass("active") == false) {
                        limitLayout();
                    }
                }
            }, 500);

            if (layout === "list") {
                isListActive = true;
                userSelectedLayout = null;
                $("#gridLayout").hide();
                $("#listLayout").show();
                $(".wrapper-control-shop").addClass("listLayout-wrapper").removeClass("gridLayout-wrapper");
            } else {
                userSelectedLayout = layout;
                setGridLayout(layout);
            }
        });
    };

    /* Loading product 
  -------------------------------------------------------------------------------------*/
    var loadProduct = function() {
        const gridInitialItems = 8;
        const listInitialItems = 4;
        const gridItemsPerPage = 4;
        const listItemsPerPage = 2;

        let listItemsDisplayed = listInitialItems;
        let gridItemsDisplayed = gridInitialItems;
        let scrollTimeout;

        function hideExtraItems(layout, itemsDisplayed) {
            layout.find(".loadItem").each(function(index) {
                if (index >= itemsDisplayed) {
                    $(this).addClass("hidden");
                }
            });
            if (layout.is("#listLayout")) updateLastVisible(layout);
        }

        function showMoreItems(layout, itemsPerPage, itemsDisplayed) {
            const hiddenItems = layout.find(".loadItem.hidden");

            setTimeout(function() {
                hiddenItems.slice(0, itemsPerPage).removeClass("hidden");
                if (layout.is("#listLayout")) updateLastVisible(layout);
                checkLoadMoreButton(layout);
            }, 600);

            return itemsDisplayed + itemsPerPage;
        }

        function updateLastVisible(layout) {
            layout.find(".loadItem").removeClass("last-visible");
            layout.find(".loadItem").not(".hidden").last().addClass("last-visible");
        }

        function checkLoadMoreButton(layout) {
            if (layout.find(".loadItem.hidden").length === 0) {
                if (layout.is("#listLayout")) {
                    $("#loadMoreListBtn").hide();
                    $("#infiniteScrollList").hide();
                } else if (layout.is("#gridLayout")) {
                    $("#loadMoreGridBtn").hide();
                    $("#infiniteScrollGrid").hide();
                }
            }
        }

        hideExtraItems($("#listLayout"), listItemsDisplayed);
        hideExtraItems($("#gridLayout"), gridItemsDisplayed);

        $("#loadMoreListBtn").on("click", function() {
            listItemsDisplayed = showMoreItems($("#listLayout"), listItemsPerPage, listItemsDisplayed);
        });

        $("#loadMoreGridBtn").on("click", function() {
            gridItemsDisplayed = showMoreItems($("#gridLayout"), gridItemsPerPage, gridItemsDisplayed);
        });

        // Infinite Scrolling
        function onScroll() {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(function() {
                const infiniteScrollList = $("#infiniteScrollList");
                const infiniteScrollGrid = $("#infiniteScrollGrid");

                if (infiniteScrollList.is(":visible") && isElementInViewport(infiniteScrollList)) {
                    listItemsDisplayed = showMoreItems($("#listLayout"), listItemsPerPage, listItemsDisplayed);
                }

                if (infiniteScrollGrid.is(":visible") && isElementInViewport(infiniteScrollGrid)) {
                    gridItemsDisplayed = showMoreItems($("#gridLayout"), gridItemsPerPage, gridItemsDisplayed);
                }
            }, 300);
        }

        function isElementInViewport(el) {
            const rect = el[0].getBoundingClientRect();
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        }
        $(window).on("scroll", onScroll);
    };

    /* Handle Dropdown Filter 
  -------------------------------------------------------------------------------------*/
    var handleDropdownFilter = function() {
        if (".wrapper-filter-dropdown".length > 0) {
            $(".btn-filterDropdown").click(function(event) {
                event.stopPropagation();
                $(".filter-drawer-wrap").toggleClass("show");
                $(this).toggleClass("active");
                var icon = $(this).find(".icon");
                if ($(this).hasClass("active")) {
                    icon.removeClass("icon-filter").addClass("icon-close");
                } else {
                    icon.removeClass("icon-close").addClass("icon-filter");
                }
                if ($(window).width() <= 1199) {
                    $(".overlay-filter").addClass("show");
                }
            });
            $(document).click(function(event) {
                if (!$(event.target).closest(".wrapper-filter-dropdown").length) {
                    $(".filter-drawer-wrap").removeClass("show");
                    $(".btn-filterDropdown").removeClass("active");
                    $(".btn-filterDropdown .icon").removeClass("icon-close").addClass("icon-filter");
                }
            });
            $(".close-filter ,.overlay-filter").click(function() {
                $(".filter-drawer-wrap").removeClass("show");
                $(".btn-filterDropdown").removeClass("active");
                $(".btn-filterDropdown .icon").removeClass("icon-close").addClass("icon-filter");
                $(".overlay-filter").removeClass("show");
            });
        }
    };

    /* Limit Layout
    -------------------------------------------------------------------------------------*/
    var limitLayout = function() {
        const gridItems = $("#gridLayout .card-product");
        const layoutClassGrid = $("#gridLayout").attr("class");
        const listItems = $("#listLayout .card-product");
        const layoutClassList = $("#listLayout").attr("class");
        let maxItems = 0;
        let maxItemList = 5;

        if (layoutClassGrid.includes("tf-col-2")) {
            maxItems = 6;
        } else if (layoutClassGrid.includes("tf-col-3")) {
            maxItems = 9;
        } else if (layoutClassGrid.includes("tf-col-4")) {
            maxItems = 12;
        } else if (layoutClassGrid.includes("tf-col-5")) {
            maxItems = 15;
        } else if (layoutClassGrid.includes("tf-col-6")) {
            maxItems = 18;
        } else if (layoutClassGrid.includes("tf-col-7")) {
            maxItems = 21;
        }

        gridItems.each(function(index) {
            if (index < maxItems) {
                $(this).css("display", "flex");
            } else {
                $(this).hide();
            }
        });

        listItems.each(function(index) {
            if (index < maxItemList) {
                $(this).css("display", "flex");
            } else {
                $(this).hide();
            }
        });

        
    };
    $(function() {
        swLayoutShop();
        loadProduct();
        handleDropdownFilter();
        limitLayout();
    });
})(jQuery);