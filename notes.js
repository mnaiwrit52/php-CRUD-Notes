let table = new DataTable('#myTable');

const edits = document.querySelectorAll(".edit");
Array.from(edits).forEach((element) =>{
    element.addEventListener("click", (e) =>{
        let title = e.target.parentNode.parentNode.querySelectorAll("td")[0].innerText;
        let description = e.target.parentNode.parentNode.querySelectorAll("td")[1].innerText;
        console.log("Edit ", title, description);
        editTitle.value = title;
        editDesc.value = description;
        document.querySelector("#snoEdit").value = e.target.id;
        console.log(e.target.id);
        $('#editModal').modal('toggle');
    })
})

const deletes = document.querySelectorAll(".delete");
Array.from(deletes).forEach((element) =>{
    element.addEventListener("click", (e) =>{
        let sno = e.target.id.substr(1,);
        if(confirm("Press a button!")){
            console.log("Yes");
            window.location = `./index.php?delete=${sno}`;
        }
        else{
            console.log("No");
        }
    })
})