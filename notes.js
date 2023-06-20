const edits = document.querySelector(".edit");
Array.from(edits).forEach((element) =>{
    element.addEventListener("click", (e) =>{
        console.log("Edit ", e);
    })
})