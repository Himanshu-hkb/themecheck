import Cookies from "js-cookie";

jQuery(document).ready(function ($) {
	"use strict";
	
	const body = $("body");
	
	const getColorScheme = () => window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
	
	const updateColorScheme = (scheme, switcher) => {
		body.addClass("trans-dis");
		setTimeout(() => body.removeClass("trans-dis"), 300);
		
		switcher.attr("data-selected-cs", scheme);
		if (scheme === "system") {
			scheme = getColorScheme();
		}
		body.attr("data-cs", scheme);
	};
	
	const handleColorSchemeSwitch = () => {
		const switcher = $(".cs-switch");
		const storedScheme = Cookies.get("jkd-cs");
		
		switcher.on("click", function () {
			const currentScheme = switcher.attr("data-selected-cs");
			const newScheme = currentScheme === "light" ? "dark" : "light";
			
			Cookies.set("jkd-cs-upd", true, {path: "/"});
			Cookies.set("jkd-cs", newScheme, {path: "/"});
			
			updateColorScheme(newScheme, switcher);
		});
		
		if (!Cookies.get("jkd-cs-upd") && getColorScheme() !== storedScheme) {
			Cookies.set("jkd-cs", getColorScheme(), {path: "/"});
			updateColorScheme(getColorScheme(), switcher);
		}
		
		window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", () => {
			const newScheme = getColorScheme();
			Cookies.set("jkd-cs", newScheme, {path: "/"});
			updateColorScheme(newScheme, switcher);
		});
	};
	
	const handleNavSearch = () => {
		const nav = $("#jkd-nav");
		const search = nav.find(".nav-search");
		if (!search.length) return;
		
		const searchToggle = nav.find(".search-toggle");
		const closeToggle = nav.find(".nav-search .btn-off, .search-toggle .btn-off");
		const inputField = search.find("input");
		
		const closeSearch = () => {
			search.removeClass("active");
			searchToggle.removeClass("active");
		};
		
		searchToggle.on("click", function () {
			if (!$(this).hasClass("active")) {
				$(this).addClass("active");
				search.addClass("active");
				inputField.focus();
			}
		});
		
		closeToggle.on("click", (e) => {
			e.stopPropagation();
			closeSearch();
		});
		
		$(document).on("keyup", (e) => {
			if (e.key === "Escape") closeSearch();
		});
	};
	
	const handleNavMobile = () => {
		const navMob = $("#jkd-nav");
		const toggle = navMob.find(".menu-tg");
		const overlay = $(".nav-ovrl");
		const closeButton = overlay.find(".btn-off");
		const menuItems = overlay.find(".menu-item");
		
		menuItems.children().children(".arrow-down").on("click", function (e) {
			e.preventDefault();
			const $this = $(this);
			const subMenu = $this.parent().parent().children(".sub-menu");
			
			$this.toggleClass("active");
			subMenu.slideToggle(150);
			subMenu.children(".menu-item").each(function (index) {
				$(this).animate({
					opacity: $this.hasClass("active") ? 1 : 0,
					top: $this.hasClass("active") ? "0px" : "10px",
				}, {
					duration: 150 + index * 50,
					queue: false,
					complete: function () {
						$(this).css("pointer-events", $this.hasClass("active") ? "auto" : "none");
					},
				});
			});
		});
		
		toggle.on("click", () => {
			overlay.addClass("active").removeClass("hidden");
			overlay.find(".menu-list").children(".menu-item").each(function (index) {
				$(this)
					.delay(index * 50)
					.animate({
						opacity: 1,
						top: "0px"
					}, 150)
					.css("pointer-events", "auto");
			});
		});
		
		closeButton.on("click", () => {
			overlay.find(".menu-list").children(".menu-item").each(function (index) {
				$(this)
					.delay(index * 50)
					.animate({
						opacity: 0,
						top: "10px"
					}, 150)
					.css("pointer-events", "none");
			});
			
			setTimeout(() => {
				overlay.removeClass("active").addClass("hidden");
				setTimeout(() => {
					overlay.find(".arrow-down").removeClass("active");
					overlay.find(".sub-menu").hide().children(".menu-item").css({
						opacity: 0,
						top: "10px",
						pointerEvents: "none",
					});
				}, 200);
			}, 150);
		});
	};
	
	handleColorSchemeSwitch();
	handleNavSearch();
	handleNavMobile();
});