// Select elements
const typeSelect = document.getElementById("type-select");
const exerciseSelect = document.getElementById("exercise-select");
const setsSelect = document.getElementById("sets-select");
const addItemBtn = document.getElementById("add-type-btn");
const clearListBtn = document.getElementById("clear-list-btn");
const typesList = document.getElementById("types-list");
const secondsInput = document.querySelector('input[name="seconds"]');
const setsInput = document.querySelector('input[name="sets"]');
const saveWorkoutBtn = document.getElementById("save-workout-btn");
const workoutNameInput = document.getElementById("workout-name");

// Disable saveWorkoutBtn initially
saveWorkoutBtn.disabled = true;

// Function to update exercise select options
function updateExerciseSelect(selectedType, callback) {
  const xhr = new XMLHttpRequest();
  xhr.open("GET", `php/get_exercises.php?type=${selectedType}`, true);
  xhr.onload = () => {
    if (xhr.status === 200) {
      const exercises = JSON.parse(xhr.responseText);
      exerciseSelect.innerHTML = `
        <option value="" disabled selected>Exercise</option>
        ${exercises.map(exercise => `<option value="${exercise.name}">${exercise.name}</option>`).join('')}
      `;
      exerciseSelect.disabled = selectedType === 'Rest';
      setsSelect.disabled = selectedType === 'Rest';
      if (callback) {
        callback();
      }
    }
  };
  xhr.send();
}

// Event listener for typeSelect change
typeSelect.addEventListener("change", () => {
  updateExerciseSelect(typeSelect.value);
});

// Event listener for addItemBtn click
addItemBtn.addEventListener("click", () => {
  const $addItemBtn = $('#add-type-btn');
  const selectedListItem = $(".selected");
  const typesArray = [];
  const newItem = document.createElement("li");

  if (typeSelect.value === "Rest" && secondsInput.value) {
    typesArray.push({
      type: typeSelect.value,
      seconds: secondsInput.value,
    });

    if ($(".selected").length > 0) {
      selectedListItem.html(`${typeSelect.value} - (${secondsInput.value}s)`);
      selectedListItem.removeClass('selected');
    } else {
      newItem.innerHTML = `${typeSelect.value} - (${secondsInput.value}s)`;
      typesList.appendChild(newItem);
      clearFields();
      saveWorkoutBtn.disabled = false;
    }
    newItem.classList.add('rest');
  } else {
    exerciseSelect.disabled = false;
    setsSelect.disabled = false;

    if (typeSelect.value && exerciseSelect.value && secondsInput.value && setsInput.value) {
      typesArray.push({
        type: typeSelect.value,
        exercise: exerciseSelect.value,
        seconds: secondsInput.value,
        sets: setsInput.value,
      });

      if ($(".selected").length > 0) {
        selectedListItem.html(`${typeSelect.value} - ${exerciseSelect.value} (${secondsInput.value}s, ${setsInput.value} sets)`);
        selectedListItem.css('background-color', '#3d3d3d');
        selectedListItem.removeClass('selected');
      } else {
        newItem.innerHTML = `${typeSelect.value} - ${exerciseSelect.value} (${secondsInput.value}s, ${setsInput.value} sets)`;
        const newNumber = typesList.children.length + 1;
        newItem.setAttribute('value', newNumber);
        typesList.appendChild(newItem);
        saveWorkoutBtn.disabled = false;
      }
    } else {
      alert("Please enter all required information.");
    }
  }

  $addItemBtn.text('Add Item');
  clearFields();
  typeSelect.focus();
});

// Sortable functionality
$(function() {
  $(".sortable").sortable({
    revert: true,
    update: function() {
      const types = $(this).children();
      types.each((index, element) => {
        element.setAttribute('value', index + 1);
      });
    }
  });
});

// Event delegation for selecting items in types-list
$(document).on('click', "#types-list li", function(event) {
  const typeText = this.innerText;
  const typeValue = typeText.split(' ')[0];
  const exerciseValue = typeText.split(' - ')[1].split(' (')[0];
  const secondsValue = (typeValue === "Rest") ? typeText.split('(')[1].split('s)')[0] : typeText.split(' (')[1].split('s, ')[0];
  const setsValue = (typeValue === "Rest") ? 0 : typeText.split(' (')[1].split('s, ')[1].split(' sets)')[0];

  const $addItemBtn = $('#add-type-btn');
  const removeItemBtn = document.createElement('button');
  removeItemBtn.textContent = 'Del';
  removeItemBtn.classList.add('copy-del-btn');
  removeItemBtn.addEventListener('click', function() {
    this.parentElement.remove();
    clearFields();
    saveWorkoutBtn.disabled = false;
    typeSelect.focus();
  });

  const exerciseSelect = document.querySelector('select[name="exercise"]');

  if (event.target.classList.contains('selected')) {
    $(this).removeClass('selected');
    exerciseSelect.disabled = false;
    setsSelect.disabled = false;
    $addItemBtn.text('Add Item');
    $(this).find('.copy-del-btn').remove();
    saveWorkoutBtn.disabled = false;
  } else {
    $('.selected').removeClass('selected').find('.copy-del-btn').remove();
    this.classList.add('selected');
    const duplicateItemBtn = document.createElement('button');
    duplicateItemBtn.textContent = 'Copy';
    duplicateItemBtn.classList.add('copy-del-btn');
    duplicateItemBtn.addEventListener('click', function() {
      const newItem = document.createElement("li");
      if (typeValue === "Rest") {
        newItem.classList.add('rest');
        newItem.innerHTML = `${typeSelect.value} - (${secondsInput.value}s)`;
      } else {
        newItem.innerHTML = `${typeValue} - ${exerciseValue} (${secondsValue}s, ${setsValue} sets)`;
      }
      const newNumber = typesList.children.length + 1;
      newItem.setAttribute('value', newNumber);
      typesList.appendChild(newItem);
    });
    this.appendChild(removeItemBtn);
    this.appendChild(duplicateItemBtn);
    $addItemBtn.text('Update Item');
    typeSelect.value = typeValue;
    secondsInput.value = secondsValue;

    if (typeValue === "Rest") {
      exerciseSelect.disabled = true;
      setsSelect.disabled = true;
    } else {
      exerciseSelect.disabled = false;
      setsSelect.disabled = false;
      updateExerciseSelect(typeValue);
      updateExerciseSelect(typeSelect.value, function() {
        exerciseSelect.value = exerciseValue;
      });
      setsInput.value = setsValue;
    }
    saveWorkoutBtn.disabled = true;
  }
});

// Function to clear input fields
function clearFields() {
  typeSelect.value = "";
  exerciseSelect.value = "";
  secondsInput.value = "";
  setsInput.value = "";
}

// Event listener for clearListBtn click
clearListBtn.addEventListener("click", clearList);

// Function to clear the types list
function clearList() {
  typesList.innerHTML = "";
  saveWorkoutBtn.disabled = true;
}

// Set focus on workout name input
workoutNameInput.focus();
