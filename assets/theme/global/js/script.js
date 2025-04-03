"use strict";
let elem = document.documentElement;

const sideBar = document.getElementById('sideContent');
const mainContent = document.getElementById('mainContent');
const menu_icon = document.getElementById('menu_icon');
const hideBarIcon = document.getElementById('hideBarIcon');
const searchBar = document.getElementById('searchBar');


$(".main-nav .li-has-children a ,.main-nav .li-has-children .bi").on('click', function (event) {

  if ($(this).hasClass('bi')) {
    $(this).toggleClass('bi-chevron-down bi-chevron-up');
  } else {
    $(this).siblings('.bi').toggleClass('bi-chevron-down bi-chevron-up');
  }

  $(this).siblings('.sub-menu').slideToggle();
  $(this).siblings('.sub-menu').toggleClass('animate');
  $(this).siblings('.sub-menu').css("height", "auto !important");

});

const notification_top = document.getElementsByClassName('notification_top');
const openFullScreen = document.getElementById('openFullScreen');
const closeFullScreen = document.getElementById('closeFullScreen');
let social_count = document.querySelectorAll('.social_count');
let currency = document.querySelectorAll('.currency');
let time = 300;




for (let i = 0; i < notification_top.length; i++) {
  notification_top[i].addEventListener('click', (e) => {
    alert('This is notification')
  })
}

const complete = () => alert('Task completed successfully');
const pending = () => alert('Task is pending now...');
const showSearchBar = () => searchBar.style.display = 'block';
const closeSearchBar = () => searchBar.style.display = 'none';


social_count.forEach(eachSocial => {
  let updateSocial = () => {
    let target1 = +eachSocial.getAttribute('data-target');
    let count1 = +eachSocial.innerText;
    let increment = target1 / time;
    if (count1 < target1) {
      eachSocial.innerText = Math.ceil(count1 + increment);
      setTimeout(updateSocial, 100)
    } else {
      eachSocial.innerText = target1
    }
  }
  updateSocial()
})
currency.forEach(eachCurrency => {
  let updateCurrency = () => {
    let target = +eachCurrency.getAttribute('data-target');
    let count = +eachCurrency.innerText;
    let increment = target / time;
    if (count < target) {
      eachCurrency.innerText = Math.ceil(count + increment);
      setTimeout(updateCurrency, 1)
    } else {
      eachCurrency.innerText = target
    }
  }
  updateCurrency()
})

var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
});

function sleep(time) {
  return new Promise((resolve) => setTimeout(resolve, time));
}
