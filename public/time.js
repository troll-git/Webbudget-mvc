/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function setToday()
{
    document.getElementById("today").valueAsDate= new Date();
}

function showDiv() {
   document.getElementById('expenses').style.display = "block";
   document.getElementById('incomes').style.display = "block";
}

