/**
 * LiveMenu, version 1.1
 *
 * Copyright (c) 2009-2010 Sergey Golubev
 *
 * LiveMenu is freely distributable under the terms of the MIT License.
 * For details, see http://livemenu.sourceforge.net
 */

var liveMenu = {};

liveMenu.defaultConfig = {
    /* Css class name of 'ul' elements, which are considered to be main menus */
    mainMenuClassName: 'lm-menu',

    /* Css class name of 'ul' elements, which are considered to be submenus */
    submenuClassName: 'lm-submenu',

    /* Css class name of a submenu container */
    containerClassName: 'lm-container',

    /* Css class names of horizontal and vertical submenus */
    horizontalClassName: 'lm-horizontal', verticalClassName: 'lm-vertical',

    /* Css class names, which determine position of a submenu */
    right: 'lm-right', left: 'lm-left', up: 'lm-up', down: 'lm-down',

    /* A delay in showing the submenus (in milliseconds)*/
    showDelay: 80,

    /* A delay in hiding a submenu (in milliseconds) */
    hideDelay: 500,

    /**
     * An event type at which a submenu should be shown. 
     * Can be 'mouseenter' or 'click'
     */
    showOn: 'mouseenter',

    /**
     * An effect that is used when showing or hiding a submenu. 
     * Can be 'plain', 'slide', 'fade' or smooth
     */
    effect: 'plain',

    /**
     * Defines the way the submenus are being hidden: simultaneously or 
     * consecutively. Can be simultaneous or consecutive.
     */
    mode: 'simultaneous',


    /**
     * The following configuration options make sense only if the 'effect'
     * option is not set to 'plain' 
     */

    /* The duration of showing or closing the submenus (in milliseconds) */
    duration: 400,

    /**
     * The maximum number of simultaneously hiding sibling submenus. Makes
     * sense only if the 'consecutive' mode is used, but regardless the mode,
     * '0' value make the submenus hide without effects. 
     */
    maxHidingSubmenus: 3,

    /* A transition algorithm. Can be 'linear' or 'sinoidal'. */
    transition: 'sinoidal'
}

liveMenu.isReady = false; //True if the DOM is loaded

liveMenu.subsCount = 0; //Used for submenu IDs generation

liveMenu.isKonqueror = navigator.userAgent.indexOf('Konqueror') != -1;

/* Initializes the menus after the DOM is loaded */
liveMenu.initOnLoad = function (menuId, config) {
	if (document.addEventListener) {
		document.addEventListener("DOMContentLoaded", function() {
            liveMenu.isReady = true;
            new liveMenu.Menu(menuId, config);
		}, false);
	} else if (document.attachEvent) {
		document.attachEvent("onreadystatechange", function() {
			if (document.readyState === "complete") {
                liveMenu.isReady = true;
                new liveMenu.Menu(menuId, config);
            }
		});
	}

    liveMenu.event.add(window, "load", function () { 
        if (!liveMenu.isReady) new liveMenu.Menu(menuId, config);
    });
}

/* The main menu constructor */
liveMenu.Menu = function (menuId, config) {
    var X = liveMenu.Utils;

    this.config = X.merge(liveMenu.defaultConfig, config);
    if (this.config.showOn == 'click')
        this.config.showDelay = 0;

    if (this.config.effect == 'plain' || this.config.maxHidingSubmenus == 0)
        this.config.mode = 'consecutive';

    this.id = menuId;
    this.domNode = document.getElementById(menuId);

    this.orientation = this.getOrientation();

    this.submenus = {};
    this.visibleSubs = [];
    this.stopHidingOn = null;

    var initSubNodes = this.getInitSubNodes();
    this.setSubIDs(initSubNodes);

    var convertedSubNodes = this.convertMenuTree(initSubNodes);
    this.initializeSubs(convertedSubNodes);
}

liveMenu.Menu.prototype = {

/* Gets initial submenu DOM nodes */
getInitSubNodes: function () {
    var X = liveMenu.Utils, cfg = this.config,
        ulNodes = this.domNode.getElementsByTagName('ul'),
        initSubNodes = [];

    for (var i=0, l=ulNodes.length; i<l; i++)
    if (X.hasClass(ulNodes[i], cfg.submenuClassName))
        initSubNodes.push(ulNodes[i]);

    return initSubNodes;
},
/* Generates and sets submenu IDs */
setSubIDs: function (initSubNodes) {
    for (var i=0, l=initSubNodes.length; i<l; i++) {
        var initSub = initSubNodes[i];

        initSub.id = 'submenu'+(++liveMenu.subsCount);

        initSub.parentNode.id = 
            'submenu'+liveMenu.subsCount+'_opener';
    }
},
/**
 * Converts an initial menu tree into the set of separate submenu nodes. 
 * For example: a submenu like:
 * <ul class="submenu">
 *  <li>item1</li>
 *  <li>item2<ul class="submenu">...</ul></li>
 * </ul>
 * Converts into:
 * <div class="container">
 *  <ul class="submenu">
 *   <li>item1</li>
 *   <li>item2</li>
 *  </ul>
 * </div>
 */
convertMenuTree: function(initSubNodes) {
    var initSub, container, sub, children, childSub,
        convertedSubNodes = [], i, j, l;

    for (i=0, l=initSubNodes.length; i<l; i++) {
        initSub = initSubNodes[i];
        sub = initSub.cloneNode(true);
        children = sub.childNodes;

        for (j=0; j<children.length; j++)
        if (children[j].tagName == 'LI') {
            if (childSub = children[j].getElementsByTagName('ul')[0])
                childSub.parentNode.removeChild(childSub);
        }

        container = document.createElement('div');
        container.className = this.config.containerClassName;
        container.appendChild(sub);
        sub.style.display = 'block';

        //Konqueror doesn't set css 'opacity' property correctly on nodes that 
        //consists only of elements with css 'float' property set to 'left'. 
        //So, horizontal submenus are not shown correctly using some effects.
        //To fix it, add an empty text node to the container of a submenu:
        if (liveMenu.isKonqueror) {
            var X = liveMenu.Utils;
            if ((this.config.effect == 'fade' || this.config.effect == 'smooth')
                && X.hasClass(sub, this.config.horizontalClassName))
            {
                container.appendChild(document.createTextNode('\u00a0'));
            }
        }

        this.domNode.parentNode.appendChild(container);

        convertedSubNodes.push(sub);
    }

    this.removeInitSubNodes();

    return convertedSubNodes;
},
/* Initializes submenu objects */
initializeSubs: function(convertedSubNodes) {
    for (var i=0, l=convertedSubNodes.length; i<l; i++) {
        var sub = convertedSubNodes[i];
        this.submenus[sub.id] = new liveMenu.Submenu(sub, this);
    }
},
/* Removes initial submenu nodes */
removeInitSubNodes: function() {
    var children = this.domNode.childNodes;

    for (var i=0; i<children.length; i++)
    if (children[i].tagName == 'LI') {
        var childSub = children[i].getElementsByTagName('ul')[0];
        if (childSub) childSub.parentNode.removeChild(childSub);
    }
},
/* Removes a submenu from 'this.visibleSubs' array */
removeFromVisibleSubs: function (sub) {
    var X = liveMenu.Utils;
    this.visibleSubs.splice(X.indexOf(sub, this.visibleSubs), 1);
},
/**
 * Forces the submenus in the process of hiding to hide immediately (without 
 * effects) if the limit of simultaneous hiding submenus is exceeded
 */
parseHidingSubs: function () {
    var limit = this.config.maxHidingSubmenus, hidingSubs = [],
        visibleSubs = this.visibleSubs;

    for (var i=0; i<visibleSubs.length; i++)
        if (visibleSubs[i].isHiding)
            hidingSubs.push(visibleSubs[i]);

    var numSubsToHide = hidingSubs.length - limit;

    if (numSubsToHide > 0)
        for (i=0; i<numSubsToHide; i++)
            hidingSubs[i].hideWithoutEffect();
},
/* Gets the group of submenus which should be shown. */
getGroupToShow: function (submenu) {
    var ancestors = submenu.getAncestors();
    var group = [submenu];
    for (var i in ancestors) {
        if (ancestors[i].isHiding) {
            group.unshift(ancestors[i]);
        }
    }
    return group;
},
/* Gets the group of submenus which should be hidden. */
getGroupToHide: function (submenu) {
    var ancestors = submenu.getAncestors();
    var group = [submenu];
    for (var i=0; ancestors[i] && ancestors[i] != this.stopHidingOn; i++) {
        group.unshift(ancestors[i]);
    }
    return group;
},
/* Gets the orientation of a submenu from its node className value */
getOrientation: function (submenuNode) {
    var X = liveMenu.Utils, cfg = this.config,
        node = submenuNode || this.domNode;
    if (X.hasClass(node, cfg.horizontalClassName)) return 'horizontal';
    if (X.hasClass(node, cfg.verticalClassName))  return 'vertical';
    return null;
}

}

/* A submenu constructor */
liveMenu.Submenu = function (domNode, menuObj) {
    this.id = domNode.id;
    this.menu = menuObj;
    this.domNode = domNode;
    this.container = domNode.parentNode;
    this.opener = document.getElementById(this.id + '_opener');
    this.parentSub = this.menu.submenus[this.opener.parentNode.id];
    this.position = this.getPosition();
    this.orientation = menuObj.getOrientation(domNode);

    this.hideTimer = null;
    this.showTimer = null;

    this.isShowing = false;
    this.isHiding = false;
    this.isSetToHide = false;

    this.addEventListeners();
}

liveMenu.Submenu.prototype = {

/* Adds all the necessary event listeners to a submenu node and its opener */
addEventListeners: function() {
    var e = liveMenu.event, showOn = this.menu.config.showOn;

    var _this = this;
    e.add(this.opener,  showOn, function (e) { _this.show(e) }, true);
    e.add(this.opener,  showOn, function (e) { _this.cancelHide(e) }, true);
    e.add(this.opener,  'mouseleave',  function (e) { _this.hide(e) });
    e.add(this.domNode, 'mouseleave',  function (e) { _this.hide(e) });

    var children = this.domNode.childNodes;
    for (var i=0; i<children.length; i++)
    if (children[i].tagName =='LI')
        e.add(children[i], 'mouseenter', function (e) { _this.cancelHide(e) });

    if (showOn == 'click') {
        var anchors = this.opener.getElementsByTagName('A');
        for (i=0; i<anchors.length; i++)
            e.add(anchors[i], 'click', function (e) { e.preventDefault() });
    }
},
/* The mouseover event listener, which is responsible for submenu showing */
show: function (e) {
    var parentSub = this.parentSub, m = this.menu;

    //If the parent submenu is in the process of showing or hiding, remember 
    //to show the submenu as soon as its parent is opened.
    if (parentSub && (parentSub.isShowing || parentSub.isHiding)) {
        m.subToShowNext = this;
        return;
    }

    var _this = this, showDelay = m.config.showDelay;
    this.showTimer = 
        setTimeout(function() { _this.doShow(false) }, showDelay);
},
/* The mouseover event listener, which cancels hiding of a submenu */
cancelHide: function (e) {
    if (!this.isVisible()) return; //show() is going to handle this event

    var ancestors = this.getAncestors();
    for (var i=0; i<ancestors.length; i++) {
        var sub = ancestors[i];
        clearTimeout(sub.hideTimer); sub.hideTimer = null;
        sub.isSetToHide = false;
    }
    var m = this.menu;
    if (this.isSetToHide) {
        clearTimeout(this.hideTimer); this.hideTimer = null;
        this.isSetToHide = false;
        m.stopHidingOn = this;
    } else if (this.isHiding) {
        m.stopHidingOn = this.parentSub;
        this.isHiding = false;
        this.doShow(true);
    } else if (m.stopHidingOn != this) {
        var lastShownSub = m.visibleSubs[m.visibleSubs.length-1];
        if (lastShownSub != this) m.stopHidingOn = this;
    }

    e.stopImmediatePropagation();
},
/* The mouseout event listener, which is responsible for submenu hiding */
hide: function(e) {
    //The following condition is possible if 'showOn' configuration
    //parameter value is 'click'
    if (this.isHiding || this.isSetToHide) return;

    //The submenu is hidden? Prevent it from showing on delay.
    if (!this.isVisible()) {
        this.menu.subToShowNext = null;
        clearTimeout(this.showTimer);
        return;
    }

    var m = this.menu;

    //Prevent the queue of hiding submenu from stopping
    m.stopHidingOn = null; 


    if (m.config.mode == 'consecutive') {
        //If the submenu has child submenus open, do not hide this submenu
        var lastShownSub = m.visibleSubs[m.visibleSubs.length-1];
        if (lastShownSub != this) return;
    }

    this.isSetToHide = true;

    var _this = this;
    this.hideTimer = 
        setTimeout(function () { _this.doHide() }, m.config.hideDelay);

    //Prevent the hide handler on the parent submenu from triggering if the 
    //mouse pointer was on the submenu opener
    e.stopPropagation();
},
/* Shows a submenu */
doShow: function (isVisible) {
    this.parseVisibleNotAncestors();

    var m = this.menu;

    if (isVisible) m.removeFromVisibleSubs(this);
    m.visibleSubs.push(this);

    var subsToShow = m.config.mode == 'simultaneous' ? m.getGroupToShow(this) 
                                                     : [this];
    for (var i in subsToShow) {
        subsToShow[i].isShowing = true;
        subsToShow[i].isHiding = false;
        if (m.config.beforeShow) m.config.beforeShow.call(subsToShow[i]);
    }
    liveMenu.Effect.In(subsToShow, m.config.effect, function () {
        var submenu, skipIt = false, m;
        //'this' is an effect object (consecutive mode)?
        if (!this.subIDs) {
            submenu = this.submenu;
            submenu.isShowing = false;
            skipIt = true;
            m = submenu.menu;
            if (m.config.afterShow) m.config.afterShow.call(submenu);
        } else {
            //'this' is a group of effect objects (simultaneous mode)
            m = this.effects[this.subIDs[0]].submenu.menu;
            for (var subId in this.effects) {
                submenu = this.effects[subId].submenu;
                submenu.isShowing = false;
                if (m.config.afterShow) m.config.afterShow.call(submenu);
            }
        }

        if (m.subToShowNext && 
            (skipIt || m.subToShowNext.parentSub.id in this.effects))
        {
            m.subToShowNext.doShow();
            m.subToShowNext = null;
        }
    });
},
/* Hides a submenu */
doHide: function () {
    var m = this.menu, subsToHide, forcePlainEffect;

    if (m.config.mode == 'simultaneous') {
        subsToHide = m.getGroupToHide(this);
        forcePlainEffect = false;
    } else {
        subsToHide = [this];
        forcePlainEffect = m.config.maxHidingSubmenus == 0 ? true : false;
    }

    for (var i in subsToHide) {
        clearTimeout(subsToHide[i].hideTimer); subsToHide[i].hideTimer = null;
        subsToHide[i].isHiding = true;
        subsToHide[i].isSetToHide = false;
        subsToHide[i].isShowing = false;
        if (m.config.beforeHide) m.config.beforeHide.call(subsToHide[i]);
    }

    if (m.config.mode != 'simultaneous' && !forcePlainEffect) {
        m.parseHidingSubs();
    }

    liveMenu.Effect.Out(subsToHide, function () {
        var m, submenu, subID;

        //'this' is a group of effect objects (simultaneous mode)?
        if (this.subIDs) {
            m = this.effects[this.subIDs[0]].submenu.menu;
            for (subID in this.effects) {
                submenu = this.effects[subID].submenu;
                submenu.isHiding = false;

                m.removeFromVisibleSubs(submenu);

                if (m.config.afterHide) m.config.afterHide.call(submenu);
            }
            return;
        }
        //'this' is an effect object (consecutive mode)
        submenu = this.submenu;
        submenu.isHiding = false;
        m = submenu.menu;

        if (submenu.parentSub) {
            var lastShownSub = m.visibleSubs[m.visibleSubs.length-1]
            if (lastShownSub == submenu &&
                m.stopHidingOn != submenu.parentSub &&
                !submenu.getVisibleNotAncestors().length)
            {
                var hideNext = true;
            }
        }

        m.removeFromVisibleSubs(submenu);

        if (m.config.afterHide) m.config.afterHide.call(submenu);

        if (hideNext) submenu.parentSub.doHide();
    }, forcePlainEffect);
},
/* Forces a submenu to hide without effects */
hideWithoutEffect: function () {
    if (this.hideTimer) {
        clearTimeout(this.hideTimer); this.hideTimer = null;
    }

    liveMenu.Effect.destroy(this);

    this.container.style.visibility = 'hidden';

    this.isShowing = false;
    this.isHiding = false;
    this.isSetToHide = false;

    var m = this.menu;

    m.subToShowNext = null;

    if (m.config.beforeHide) m.config.beforeHide.call(this);

    m.removeFromVisibleSubs(this);

    if (m.config.afterHide) m.config.afterHide.call(this);
},
/**
 * Forces all descendants of the parent submenu (except current submenu), which
 * are not in the process of hiding to hide
 */
parseVisibleNotAncestors: function () {
    var vnas  = this.getVisibleNotAncestors();

    if (!vnas.length) return;

    var m = this.menu;
    if (m.config.mode == 'simultaneous') {
        var X = liveMenu.Utils,
            lastShownSub = m.visibleSubs[m.visibleSubs.length-1];

        if (!lastShownSub.isHiding && X.indexOf(lastShownSub, vnas) != -1)
            lastShownSub.doHide();

        for (var i=0; i<vnas.length; i++)
            if (!vnas[i].isHiding)
                vnas[i].doHide();
        return;
    }

    if (vnas.length === 1) {
        if (!vnas[0].isHiding) 
            vnas[0].doHide();
        return;
    }

    var parent = this.parentSub || m, vna, vnaParent, subToHide; 

    for (var i=0; i<vnas.length; i++) {
        vna = vnas[i];
        vnaParent = vna.parentSub || m;
        if (vnaParent != parent) {
            vna.hideWithoutEffect();
        } else if (!vna.isHiding) {
            subToHide = vna;
        }
    }
    if (subToHide) subToHide.doHide();
},
/* Gets the position of a submenu from its node className value */
getPosition: function () {
    var X = liveMenu.Utils, cfg = this.menu.config,
        sub = this.domNode;
    if (X.hasClass(sub, cfg.right)) return 'right';
    if (X.hasClass(sub, cfg.down))  return 'down';
    if (X.hasClass(sub, cfg.up))    return 'up';
    if (X.hasClass(sub, cfg.left))  return 'left';
    return null;
},
/* Gets ancestor submenus of the current submenu */
getAncestors: function () {
    var ancestorSubs = [];
    var parent = this.parentSub;
    while (parent != null) {
        ancestorSubs.push(parent);
        parent = parent.parentSub;
    }
    return ancestorSubs;
},
/* Gets visible submenus, which are not ancestors of the current submenu */
getVisibleNotAncestors: function () {
    var X = liveMenu.Utils;
    var vnas = [];
    var ancestorSubs = this.getAncestors();
    var visibleSubs = this.menu.visibleSubs;

    ancestorSubs.push(this);

    for (var i=0; i<visibleSubs.length; i++)
        if (X.indexOf(visibleSubs[i], ancestorSubs) == -1)
            vnas.push(visibleSubs[i]);

    return vnas;
},
/* Checks if a submenu is visible */
isVisible: function () {
    return this.container.style.visibility == 'visible';
}

}

liveMenu.Effect = {

/* The effect objects storage */
effects: {},

/* An array of the 'liveMenu.Effect.group' objects */
groups: [],

zIndex: 100,

Transitions: {
    linear: function (pos) { return pos },
    sinoidal: function (pos) { return (-Math.cos(pos*Math.PI)/2) + .5 }
},

/**
 * Starts effect rendering and calculates the progress of it. Passes the
 * progress value to the render function of the effect object.
 */
loop: function (effectObj, direction, callback) {
    var e = effectObj;
    if (direction) {
        e.direction = direction;
        e.callback = callback;

        if (e.intervalId) { clearInterval(e.intervalId); e.intervalId = null; }

        var now = (new Date()).getTime();
        e.startOn = e.finishOn ? (2*now - e.finishOn) : now;
        e.finishOn = e.startOn + e.duration;

        e.render(null);

        e.intervalId = 
            setInterval(function () { liveMenu.Effect.loop(e) }, e.interval);
    } else {
        var now = (new Date()).getTime();
        if (now >= e.finishOn) {
            clearInterval(e.intervalId); e.intervalId = null;
            e.finishOn = e.startOn = null;
            e.render(1.0);
            e.callback.call(e);
        } else {
            var p = (now - e.startOn)/(e.finishOn - e.startOn);
            e.render(this.Transitions[e.transition](p));
        }
    }
},
/* Gets the index of a group object in 'this.groups' array by the group ID  */
getGroupIndex: function (groupID) {
    for (var i in this.groups) if (this.groups[i].id == groupID) return i;
    return null;
},
/* Makes up an array of the submenu IDs in a group, and returns it */
getSubIDs: function (groupOfSubs) {
    var subIDs = [];
    for (var i=0; i<groupOfSubs.length; i++) {
        subIDs.push(groupOfSubs[i].id);
    }
    return subIDs;
},
/* Gets the string representation of an array of the submenus IDs */
getGrpID: function (subIDs) {
    return subIDs.join(' ')+' ';
},
/* Shows a submenu with effect 'effectName' */
In: function (subsToShow, effectName, callback) {
    var m = subsToShow[0].menu, i;
    this.zIndex++;
    for (i in subsToShow) subsToShow[i].container.style.zIndex = this.zIndex;

    if (m.config.mode == 'simultaneous') {
        var X = liveMenu.Utils, grp,
            grpSubIDs = this.getSubIDs(subsToShow),
            grpID = this.getGrpID(grpSubIDs);
        for (i in this.groups) {
            grp = this.groups[i];
            if (grpID.indexOf(grp.id) == -1 && 
                X.indexOf(grpSubIDs[grpSubIDs.length-1], grp.subIDs) != -1)
            { 
                grp.divide(grpSubIDs[grpSubIDs.length-1]);
                break;
            }
        }

        var needToCreateNewGroup = true;
        for (i in this.groups) {
            grp = this.groups[i];
            if (grpID.indexOf(grp.id) != -1) {
                this.loop(grp, 'in', callback);
                needToCreateNewGroup = false;
            }
        }
        if (needToCreateNewGroup) {
            this.groups.push(new this.group(grpID, grpSubIDs, subsToShow, effectName));
            this.loop(this.groups[this.groups.length-1], 'in', callback);
        }
    } else {
        var submenu = subsToShow[0];
        if (this.effects[submenu.id] == null)
            this.effects[submenu.id] = new this[effectName](submenu);

        if (effectName == 'plain') {
            this.effects[submenu.id].render('in');
            callback.call(this.effects[submenu.id]);
        } else {
            this.loop(this.effects[submenu.id], 'in', callback);
        }
    }
},
/* Hides a submenu with the effect */
Out: function (subsToHide, callback, forcePlainEffect) {
    var m = subsToHide[0].menu;
    if (m.config.mode == 'consecutive') {
        var submenu = subsToHide[0];
        if (forcePlainEffect) {
            this.destroy(submenu);
            this.effects[submenu.id] = new this.plain(submenu);
        }
        var e = this.effects[submenu.id];
        if (e.type == 'plain') {
            e.render('out');
            callback.call(e);
            if (forcePlainEffect) this.destroy(submenu);
        } else {
            this.loop(this.effects[submenu.id], 'out', callback);
        }
        return;
    }

    var X = liveMenu.Utils, grp,
        group = subsToHide,
        grpSubIDs = this.getSubIDs(group),
        grpID = this.getGrpID(grpSubIDs),

        grpIntersect = [];

    for (var grpIndex=0, l=this.groups.length; grpIndex < l; grpIndex++) {
        grp = this.groups[grpIndex];
        if (grpID != grp.id) {
            //If the current group of subs(grp) contains the group 
            //given(group) and it is showing now, divide it into two groups:
            if (grp.direction == 'in' && grp.id.indexOf(grpID) != -1) {
                var first = grpSubIDs[0],
                    divSubIndex = X.indexOf(first, grp.subIDs)-1;
                grp.divide(grp.subIDs[divSubIndex]);
            }
            else {
                intersect = this.getGroupsIntersection(grpSubIDs, grp.subIDs);
                //The group given intersects with the current group?
                if (intersect.length) {
                    var g1 = grp.subIDs, g2 = grpSubIDs;
                    //If the current group contains submenus which are 
                    //ancestors to all submenus of the group given, 
                    //remove them from the current group
                    if (X.indexOf(g1[g1.length-1], g2) != -1 && 
                        X.indexOf(g1[0], g2) == -1)
                    {
                        grp.divide(g1[X.indexOf(g2[0], g1)-1]);
                        grp = this.groups[this.groups.length-1];
                    }
                    //Make the current group begin to hide
                    this.loop(grp, 'out', callback);

                    grpIntersect = grpIntersect.concat(intersect);
                }
            }
        }
    }
    //Remove the common submenus from the group given
    for (var i in grpIntersect) {
        var key = X.indexOf(grpIntersect[i], grpSubIDs);
        if (key != -1) {
            group.splice(key, 1);
            grpSubIDs = this.getSubIDs(group);
        }
    }

    if (!group.length) return;

    grpID = this.getGrpID(grpSubIDs);

    if (!(grpIndex = this.getGroupIndex(grpID))) {
        this.groups.push(new this.group(grpID, grpSubIDs, group));
        grpIndex = this.groups.length-1;
    }
    this.loop(this.groups[grpIndex], 'out', callback);
},
/* Gets tha array of common submenus between two groups */
getGroupsIntersection: function (g1IDs, g2IDs) {
    var X = liveMenu.Utils,
        start = X.indexOf(g1IDs[0], g2IDs), intersect = [];
    if (start != -1) {
        intersect = g2IDs.slice(start, start+g1IDs.length);
    } else {
        start = X.indexOf(g2IDs[0], g1IDs);
        if (start != -1) {
            intersect = g1IDs.slice(start, start+g2IDs.length);
        }
    }
    return intersect;
},
/* Destroys the effect object */
destroy: function (submenu) {
    var effect = this.effects[submenu.id];

    if (effect && effect.intervalId) clearInterval(effect.intervalId);

    this.effects[submenu.id] = null;
},
/* Places a submenu container to on its target position */
setContainerPos: function (submenu) {
    var containerStyle = submenu.container.style;
    var targetCoords = this.getTargetCoords(submenu);
    containerStyle.left = targetCoords.left+'px';
    containerStyle.top = targetCoords.top+'px';
},
/* Gets target coordinates of a submenu */
getTargetCoords: function(subObj) {
    var X = liveMenu.Utils, o = subObj.opener;

    switch (subObj.position) {
        case 'right': return {
            left: X.getOffsetPos(o, 'Left') + o.offsetWidth,
            top: X.getOffsetPos(o, 'Top')
        };
        case 'down': return {
            left: X.getOffsetPos(o, 'Left'),
            top: X.getOffsetPos(o, 'Top') + o.offsetHeight
        };
        case 'left': return {
            left: X.getOffsetPos(o, 'Left') - subObj.domNode.offsetWidth,
            top: X.getOffsetPos(o, 'Top')
        };
        case 'up': return {
            left: X.getOffsetPos(o, 'Left'),
            top: X.getOffsetPos(o, 'Top') - subObj.domNode.offsetHeight
        }
    }
},
/* Places a submenu node on its initial position (for sliding effects) */
setSubInitPos: function (submenu) {
    var sub = submenu.domNode;
    switch (submenu.position) {
        case 'right': sub.style.left = -sub.offsetWidth+'px'; return;
        case 'down': sub.style.top = -sub.offsetHeight+'px'; return;
        case 'left': sub.style.left = sub.offsetWidth+'px'; return;
        case 'up': sub.style.top = sub.offsetHeight+'px'; return;
    }
}

}

/* The 'plain' effect constructor */
liveMenu.Effect.plain = function (submenu) {
    this.type = 'plain';
    this.submenu = submenu;
    this.container = submenu.container;
    liveMenu.Effect.setContainerPos(submenu);
}
liveMenu.Effect.plain.prototype = {
    render: function(direction) {
        this.container.style.visibility = direction == 'in'
            ? 'visible' : 'hidden';
    }
}

/* The 'group' object constructor */
liveMenu.Effect.group = function (id, submenuIDs, submenus, effectObjOrName) {
    var cfg = submenus[0].menu.config;
    this.subIDs = submenuIDs;
    this.id = id;
    this.duration = cfg.duration;
    this.transition = cfg.transition;
    this.interval = 20;

    if (typeof(effectObjOrName) == 'object') {
        this.effects = effectObjOrName;
    } else {
        var effectName = effectObjOrName,
            effects = liveMenu.Effect.effects;

        this.effects = {};
        
        for (var i=0; i<submenus.length; i++) {
            var sub = submenus[i];
            if (!(sub.id in effects)) {
                effects[sub.id] = new liveMenu.Effect[effectName](sub);
            }
            this.effects[sub.id] = effects[sub.id];
        }
    }
}
liveMenu.Effect.group.prototype = {

/**
 * Renders all the submenus in the group depending on the progress value 
 * received from liveMenu.Effect.loop() function using
 */
render: function(progress) {
    var subID, effects = this.effects;
    if (progress == null) {
        for (subID in effects) {
            effects[subID].direction = this.direction;
        }
    }
    for (subID in effects) if (effects[subID]) {
        effects[subID].render(progress);
    }
    if (progress == 1.0) {
        var grpIndex = liveMenu.Effect.getGroupIndex(this.id);
        liveMenu.Effect.groups.splice(grpIndex, 1);
    }
},
/* Divides the group. The submenu with id 'submenuID' is the separator */
divide: function(submenuID) {
    var groupPart1 = [], groupPart2 = [], effectsPart1 = {}, effectsPart2 = {},
        groupPart = groupPart1, effectsPart = effectsPart1;

    for (var subID in this.effects) {
        groupPart.push(this.effects[subID].submenu); 
        effectsPart[subID] = this.effects[subID];
        if (subID == submenuID) {
            groupPart = groupPart2;
            effectsPart = effectsPart2;
        }
    }

    var grpIndex = liveMenu.Effect.getGroupIndex(this.id),
        subIDs1 = liveMenu.Effect.getSubIDs(groupPart1),
        subIDs2 = liveMenu.Effect.getSubIDs(groupPart2),
        grp1ID = liveMenu.Effect.getGrpID(subIDs1),
        grp2ID = liveMenu.Effect.getGrpID(subIDs2);

    if (this.direction == 'in') {
        this.subIDs = subIDs1; this.id = grp1ID;
        this.effects = effectsPart1;
    } else {
        this.subIDs = subIDs2; this.id = grp2ID;
        this.effects = effectsPart2;
    }

    var e = liveMenu.Effect;
    e.groups.push(this);
    e.groups.splice(grpIndex, 1);

    e.groups.push(this.direction == 'in' ?
        new e.group(grp2ID, subIDs2, groupPart2, effectsPart2) :
        new e.group(grp1ID, subIDs1, groupPart1, effectsPart1)
    );

    e.groups[e.groups.length-1].finishOn = this.finishOn;
}

}

/* The 'fade' effect constructor */
liveMenu.Effect.fade = function (submenu) {
    var cfg = submenu.menu.config;
    this.submenu = submenu;
    this.duration = cfg.duration;
    this.transition = cfg.transition;
    this.interval = 100;

    liveMenu.Effect.setContainerPos(submenu);

    var containerStyle = submenu.container.style;
    containerStyle.opacity = '0.0';
    containerStyle.zoom = 1;
}
liveMenu.Effect.fade.prototype = {

/**
 * Renders the submenu using the effect, depending on the progress value 
 * received from liveMenu.Effect.loop() function
 */
render: function (progress) {
    var containerStyle = this.submenu.container.style;
    if (progress == null) {
        if (!this.submenu.isVisible()) {
            containerStyle.visibility = 'visible';
            containerStyle.filter = 'alpha(opacity: 0)';
        }
    } else {
        var opacity = this.direction == 'in' ? progress : 1.0 - progress;
        opacity = opacity.toFixed(1);

        containerStyle.opacity = opacity;
        containerStyle.filter = 'alpha(opacity='+opacity*100+')';

        if (progress === 1.0) {
            if (this.direction == 'out')
                containerStyle.visibility = 'hidden';
            else
                containerStyle.filter = '';
        }
    }
}

}

/* The 'smooth' effect constructor */
liveMenu.Effect.smooth = function (submenu) {
    liveMenu.Effect.slide.call(this, submenu);
    var containerStyle = submenu.container.style;
    containerStyle.opacity = '0.0';
    containerStyle.zoom = 1;
    containerStyle.visibility = 'visible';
    containerStyle.filter = 'alpha(opacity: 0)';
}

liveMenu.Effect.smooth.prototype = {

render: function (progress) {
    liveMenu.Effect.slide.prototype.render.call(this, progress);
    if (progress == null) {
        this.prevProgress = 0;
    } else if (progress >= this.prevProgress+0.1 || progress == 1) {
        this.prevProgress = progress;
        liveMenu.Effect.fade.prototype.render.call(this, progress);
    }
}

}

/* The 'slide' effect constructor */
liveMenu.Effect.slide = function (submenu) {
    var cfg = submenu.menu.config;
    this.submenu = submenu;
    this.duration = cfg.duration;
    this.transition = cfg.transition;
    this.interval = 20;

    liveMenu.Effect.setContainerPos(submenu);

    liveMenu.Effect.setSubInitPos(submenu);

    if (submenu.position == 'left' || submenu.position == 'right') {
        this.condData = {
            initCoord: parseInt(submenu.domNode.style.left),
            x1: submenu.position == 'left' ? -submenu.container.offsetWidth : submenu.opener.offsetWidth,
            x2: 'up',
            x3: 'offsetTop',
            x4: 'offsetHeight',
            x5: 'down',
            x6: 'Top',
            x7: 'Left',
            x8: 'top',
            x9: 'left',
            x10: 'horizontal',
            x11: 'offsetWidth'
        };
    } else {
        this.condData = {
            initCoord: parseInt(submenu.domNode.style.top),
            x1: submenu.position == 'up' ? -submenu.container.offsetHeight : submenu.opener.offsetHeight,
            x2: 'left',
            x3: 'offsetLeft',
            x4: 'offsetWidth',
            x5: 'right',
            x6: 'Left',
            x7: 'Top',
            x8: 'left',
            x9: 'top',
            x10: 'vertical',
            x11: 'offsetHeight'
        };
    }
}
liveMenu.Effect.slide.prototype = {

/**
 * Renders the submenu using the effect, depending on the progress value 
 * received from liveMenu.Effect.loop() function
 */
render: function (progress) {
    if (progress == null) {
        var containerStyle = this.submenu.container.style;
        if (!this.submenu.isVisible()) containerStyle.visibility = 'visible';
    } else {
        var X = liveMenu.Utils,
            d = this.condData,
            submenu = this.submenu, subNode = submenu.domNode,
            opener = submenu.opener, container = submenu.container,
            parentSub = submenu.parentSub,

            coord = this.direction == 'in'
                ? Math.floor(subNode[d.x11] * progress)
                : subNode[d.x11] - Math.floor(subNode[d.x11] * progress);

        if (parentSub) {
            var pNode = parentSub.domNode, c = true;
            if (parentSub.position == d.x2)
                c = pNode[d.x3] <= pNode[d.x4]-opener[d.x3]-opener[d.x4];
            else if (parentSub.position == d.x5)
                c = pNode[d.x3]*-1 <= opener[d.x3];
            if (c) {
                var p = X.getOffsetPos(opener, d.x6);
                container.style[d.x8] = p+'px';
            } else {
                var ancestorSub = parentSub.parentSub;
                if (ancestorSub && ancestorSub.orientation == d.x10) {
                    var aNode = ancestorSub.domNode, aPos = X.getOffsetPos(aNode, d.x6);
                    container.style[d.x8] = parentSub.position == d.x2 ?
                        (aPos - aNode[d.x4])+'px' :
                        (aPos + aNode[d.x4])+'px';
                }
            }
            container.style[d.x9] = (X.getOffsetPos(opener, d.x7) + d.x1) + 'px';
        }

        subNode.style[d.x9] = d.initCoord < 0
            ? (d.initCoord+coord)+'px'
            : (d.initCoord-coord)+'px';

        if (progress === 1.0 && this.direction == 'out')
            container.style.visibility = 'hidden';
    }
}

}

/* Some functions for managing events */
liveMenu.event = {
    /* An array of elements the event handlers attached to */
    elements: [],

    /**
     * A collection of the event handlers with the structure:
     * { 'element index 1': { 
     *      'event type 1': ['handler1', 'handler2',...],
     *      'event type 2': ['handler1', 'handler2',...],
     *      ... 
     *   },
     *   'element index 2': { ... },
     *   ...
     * }
     */
    handlers: {},

    /* Adds event handlers */
    add: function (elem, evType, fn, addFirst) {
        var X = liveMenu.Utils, elemIndex = X.indexOf(elem, this.elements);

        if (elemIndex === -1) {
            this.elements.push(elem);
            elemIndex = this.elements.length-1;
            this.handlers[elemIndex] = {};
        }

        if (!this.handlers[elemIndex][evType]) {
            this.handlers[elemIndex][evType] = [];

            var originalEventType = evType == 'mouseenter' ? 'mouseover' : 
                evType == 'mouseleave' ? 'mouseout' : evType;

            var handler = function (event) {
                var e = new liveMenu.Event(
                    event || window.event, evType);
                liveMenu.event.handle.call(arguments.callee.elem, e); 
            }
            handler.elem = elem;

            if (elem.addEventListener)
                elem.addEventListener(originalEventType, handler, false);
            else if (elem.attachEvent)
                elem.attachEvent('on' + originalEventType, handler);
            else
                elem['on' + originalEventType] = handler;
        }
        elem = null;

        if (addFirst)
            this.handlers[elemIndex][evType].unshift(fn);
        else
            this.handlers[elemIndex][evType].push(fn);
    },
    /* Handles the events */
    handle: function (e) {
        var X = liveMenu.Utils, E = liveMenu.event,
            elemIndex = X.indexOf(this, E.elements),
            handlers = E.handlers[elemIndex][e.type];

        for (var i in handlers) {
            var handler = handlers[i];
            if (e.type == 'mouseenter' || e.type == 'mouseleave') {
                var parent = e.relatedTarget;
                while ( parent && parent != this )
                    parent = parent.parentNode;

                if (parent == this) return;
            }
            handler.call(this, e);

            if (e.isImmediatePropagationStopped) return;
        }
    }
}

/* A wrapper of the original event object */
liveMenu.Event = function (srcEvent, evType) {
    this.originalEvent = srcEvent;
    this.type = evType;

    if (srcEvent.relatedTarget)
        this.relatedTarget = srcEvent.relatedTarget;
    else if (srcEvent.fromElement)
        this.relatedTarget = srcEvent.fromElement == srcEvent.srcElement 
            ? srcEvent.toElement : srcEvent.fromElement;
}
liveMenu.Event.prototype = {
    stopPropagation: function () {
        var e = this.originalEvent;
        e.stopPropagation ? e.stopPropagation() : e.cancelBubble = true;
    },
    stopImmediatePropagation: function () {
        this.isImmediatePropagationStopped = true;
        this.stopPropagation();
    },
    preventDefault: function () {
        var e = this.originalEvent;
        e.preventDefault ? e.preventDefault() : e.returnValue = false;
    }
}

/* A collection of utility functions */
liveMenu.Utils = {

merge: function (obj1, obj2) {
    if (!obj2) return obj1;
    for (var prop in obj1) if (!obj2.hasOwnProperty(prop)) {
        obj2[prop] = obj1[prop];
    }
    return obj2;
},
hasClass: function (el, className) {
    var pattern = new RegExp('(^|\\s)'+className+'(\\s|$)');
    if (el && el.className && pattern.test(el.className))
        return true;
    return false;
},
getOffsetPos: function (el, pos) {
    var res = 0;
    while (el != null) {
        res += el['offset'+pos];
        el = el.offsetParent;
    }
    return res;
},
indexOf: function(elt, arr) {
    if (Array.prototype.indexOf) return arr.indexOf(elt);
    for (var pos=0; pos<arr.length; pos++)
        if (arr[pos] === elt) return pos;
    return -1;
}

}
