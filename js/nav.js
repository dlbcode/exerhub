// Define separate arrays for sideNav and topNav items
const sideNavItems = [
  { title: 'Method', href: '#' },
  { title: 'Progressions', href: '/progressions.php' },
  { title: 'Exercises', href: '/exercises.php' },
  { title: 'Workouts', href: '/workouts.php' },
  { title: 'Logs', href: '/logs.php' },
];

const siteHierarchy = {
  "index.php": null,
  "login.php": "index.php",
  "create_account.php": "login.php",
  "account.php": "index.php",
  "workouts.php": "index.php",
  "workout.php": "workouts.php",
  "create_workout.php": "workouts.php",
  "edit_workout.php": "workouts.php",
  "select_workouts.php": "workouts.php",
  "workout_player.php": "workout.php",
  "logs.php": "index.php",
  "workout_logs.php": "logs.php",
  "workout_log.php": "workout_logs.php",
  "edit_workout_log.php": "workout_logs.php",
  "exercises.php": "index.php",
  "progressions.php": "exercises.php",
};

let topNavItems = [
  { title: 'arrow_back', href: 'javascript:;', class: 'back-button' },
];
if (window.location.pathname.includes('create_account.php')) {
  topNavItems = topNavItems.filter(item => item.title !== 'Create Account');
}
if (window.location.pathname.includes('login.php')) {
  topNavItems = topNavItems.filter(item => item.title !== 'Log In');
}
if (window.location.pathname.includes('index.html') || window.location.pathname.includes('index.php')) {
  topNavItems = topNavItems.filter(item => item.title !== 'arrow_back');
}
// Function to fetch session variables from the server
function fetchSessionVars() {
  return new Promise((resolve, reject) => {
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          const sessionVars = JSON.parse(xhr.responseText);
          resolve(sessionVars);
        } else {
          reject();
        }
      }
    };
    xhr.open('GET', '/php/get_session_vars.php', true);
    xhr.send();
  });
}
// Function to update navigation items based on session variables
function updateNavigation(sessionVars) {
  window.sessionVars = sessionVars;
  console.log(sessionVars);
  //if (sessionVars.userName) {
  //  topNavItems = topNavItems.filter(item => item.title !== 'Create Account');
  //  topNavItems = topNavItems.filter(item => item.title !== 'Log In');
  //  topNavItems.push({ title: 'Logout', href: 'php/logout.php' });
  //}
  const topNav = document.querySelector('#top-nav');
  const sideNav = document.querySelector('#side-nav');
  // Initialize sideNav
  var elems = document.querySelectorAll('.sidenav');
  var instances = M.Sidenav.init(elems);
  // Clear existing items
  sideNav.innerHTML = '';
  topNav.innerHTML = '';
  // Add items to sideNav
  sideNavItems.forEach((item) => {
    const li = document.createElement('li');
    const a = document.createElement('a');
    a.textContent = item.title;
    a.href = item.href;
    li.className = 'no-style';
    li.appendChild(a);
    sideNav.appendChild(li);
  });

 // Add my account link to sideNav
 addSpace();
 if (sessionVars.userName) {
  // if the the user is an admin, add admin, add a link to the admin page
  if (sessionVars.isAdmin && window.location.pathname.includes('admin') === false) {
    const adminLink = document.createElement('a');
    adminLink.textContent = 'Admin';
    adminLink.style.display = 'block';
    adminLink.className = 'btn';
    adminLink.href = 'admin/index.html';
    sideNav.appendChild(adminLink);
  }
  addSpace();
  const usernameLink = document.createElement('a');
  usernameLink.textContent = 'My Account';
  usernameLink.style.display = 'block';
  usernameLink.className = 'btn';
  usernameLink.href = '/account.php';
  sideNav.appendChild(usernameLink);
// Add logout link to sideNav
  addSpace();
  const logoutLink = document.createElement('a');
  logoutLink.textContent = 'Logout';
  logoutLink.style.display = 'block';
  logoutLink.className = 'btn';
  logoutLink.href = '/php/logout.php';
  sideNav.appendChild(logoutLink);
} else {
  // Add login link to sideNav
  const loginLink = document.createElement('a');
  loginLink.textContent = 'Login';
  loginLink.style.display = 'block';
  loginLink.className = 'btn';
  loginLink.href = '/login.php';
  sideNav.appendChild(loginLink);
  addSpace();
  const createAccountLink = document.createElement('a');
  createAccountLink.textContent = 'Create Account';
  createAccountLink.style.display = 'block';
  createAccountLink.className = 'btn';
  createAccountLink.href = '/create_account.php';
  sideNav.appendChild(createAccountLink);
}

// create a function to add 5px space
function addSpace() {
  const space = document.createElement('div');
  space.style.height = '5px';
  sideNav.appendChild(space);
}

  // Add items to topNav
topNavItems.forEach((item) => {
  const a = document.createElement('a');
  a.href = item.href;
  a.className = item.class;

  const span = document.createElement('span');
  span.className = 'material-icons';
  span.textContent = item.title;

  a.appendChild(span);

  a.onclick = function(event) {
    event.preventDefault(); // Prevent default navigation
    if (item.title === 'arrow_back') {
      const currentURL = window.location.pathname.split('/').pop();
      const parentURL = siteHierarchy[currentURL] || null;
      if (parentURL) {
        window.location.href = parentURL;
      }
    }
  };  
  
  topNav.appendChild(a);
});
  // Add CSS style to align the buttons to the right
  topNav.style.textAlign = 'right';
  // Make top-nav always visible
  topNav.classList.remove('hide');
}
document.addEventListener('DOMContentLoaded', function () {
  fetchSessionVars().then(sessionVars => {
    updateNavigation(sessionVars);
    // Redirect to index.php if the user is logged in, not in the admin section and is on index.html
    if (sessionVars.userId && (!window.location.pathname.includes('admin') && window.location.pathname.includes('index.html'))) {
      window.location.href = 'index.php';
    }
  });
  // Get the current page title and URL
  const pageTitle = document.title;
  const pageURL = window.location.pathname.split('/').pop();
  // Get the dynamic-navbar element
  const dynamicNavbar = document.getElementById("dynamic-navbar");
  // Create the dynamic content
  const a = document.createElement('a');
  a.href = pageURL;
  a.textContent = pageTitle;
  // Append the dynamic content to the navbar
  dynamicNavbar.appendChild(a);
});
