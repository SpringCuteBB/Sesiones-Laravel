const labelAddSession = document.getElementById("labelAddSession");
const buttonAddSession = document.getElementById("buttonAddSession");
const colorInput = document.getElementById("color");
const colorTextoInput = document.getElementById("colorTexto");
const letraInput = document.getElementById("letra");
const tamanyoInput = document.getElementById("tamanyo");
const preview = document.getElementById("preview");
const formSesion = document.getElementById("form-sesion");
const createButtonZero = document.getElementById("createButtonZero");
const importarButton = document.getElementById("importarButton");

//modos
let addSessionOpen = false;

const buttonDeleteSessionFunc = () => {
    const allBtns = document.querySelectorAll(".buttonDeleteSession");
    allBtns.forEach((btn) => {
        btn.addEventListener("click", () => {
            const dataId = btn.dataset.id;
            const session = document.getElementById("sesion-" + dataId);
            const headerNewSession = document
                .getElementById("sesion-" + (dataId - 1))
                .querySelector(".header-sesion");
            console.log(headerNewSession);
            headerNewSession.appendChild(buttonAddSession);
            headerNewSession.appendChild(labelAddSession);
            session.remove();
            --sessionCounter;
            console.log(sessionCounter + " " + dataId);

            if (sessionCounter == 1) {
                document
                    .querySelector("#buttonDeleteSession-" + sessionCounter)
                    .classList.remove("flex");
                document
                    .querySelector("#buttonDeleteSession-" + sessionCounter)
                    .classList.add("hidden");
            } else {
                document
                    .querySelector("#buttonDeleteSession-" + sessionCounter)
                    .classList.remove("hidden");
                document
                    .querySelector("#buttonDeleteSession-" + sessionCounter)
                    .classList.add("flex");
            }
            fetch("/delete/" + dataId, {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            });
        });
    });
};
buttonDeleteSessionFunc();

buttonAddSession.addEventListener("click", () => {
    if (!addSessionOpen) {
        labelAddSession.classList.remove("hidden");
        labelAddSession.classList.add("grid");
        addSessionOpen = true;
    } else {
        labelAddSession.classList.remove("grid");
        labelAddSession.classList.add("hidden");
        addSessionOpen = false;
    }
});

const colorInputFunc = (id) => {
    const allColorInputs = document.querySelectorAll(".color");
    allColorInputs.forEach((colorInput, index) => {
        colorInput.addEventListener("change", () => {
            document.getElementById(
                "preview-" + (index + 1)
            ).style.backgroundColor = colorInput.value;
        });
    });
};
colorInputFunc();

const colorTextoInputFunc = (id) => {
    const allColorTextoInputs = document.querySelectorAll(".colorTexto");
    allColorTextoInputs.forEach((colorTextoInput, index) => {
        colorTextoInput.addEventListener("change", () => {
            document.getElementById(
                "preview-" + (index + 1)
            ).childNodes[1].style.color = colorTextoInput.value;
        });
    });
};
colorTextoInputFunc();

const letraInputFunc = (id) => {
    const allLetraInputs = document.querySelectorAll(".letra");
    allLetraInputs.forEach((letraInput, index) => {
        letraInput.addEventListener("change", () => {
            document.getElementById(
                "preview-" + (index + 1)
            ).childNodes[1].style.fontFamily = `${letraInput.value}`;
        });
    });
};
letraInputFunc();

const tamanyoInputFunc = (id) => {
    const allTamanyoInputs = document.querySelectorAll(".tamanyo");
    allTamanyoInputs.forEach((tamanyoInput, index) => {
        tamanyoInput.addEventListener("input", () => {
            document.getElementById(
                "preview-" + (index + 1)
            ).childNodes[1].style.fontSize = `${tamanyoInput.value}%`;
        });
    });
};
tamanyoInputFunc();

const ExportJSON = (id) => {
    const allExportBtns = document.querySelectorAll(".export-btn");
    allExportBtns.forEach((exportBtn, index) => {
        exportBtn.addEventListener("click", () => {
            const form = document.getElementById("formSesion-" + (index + 1));
            const data = {
                color: form.color.value,
                colorTexto: form.colorTexto.value,
                letra: form.letra.value,
                tamanyo: form.tamanyo.value,
            };
            const dataStr = JSON.stringify(data);
            console.log(dataStr);

            const blob = new Blob([dataStr], { type: "application/json" });
            const url = URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = url;
            a.download = `session-${index + 1}.json`; // Nombre del archivo
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url); // Liberar el objeto URL
        });
    });
};
ExportJSON();

importarButton.addEventListener("click", () => {
    document.getElementById("importFile").click();
});

document.getElementById("importFile").addEventListener("change", (event) => {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            try {
                const data = JSON.parse(e.target.result);
                //----------------------------------------------
                console.log("Datos importados correctamente:", data);
                // Clonar el div de la sesión
                const originalSession = document.getElementById(
                    "sesion-" + sessionCounter
                );
                sessionCounter++;
                const newSession = originalSession.cloneNode(true);

                // Cambiar los IDs y data-ids en el nuevo div
                newSession.id = `sesion-${sessionCounter}`;
                newSession.dataset.id = sessionCounter;
                newSession.querySelector(
                    "#formSesion-" + (sessionCounter - 1)
                ).dataset.id = sessionCounter;
                newSession.querySelector(
                    "#preview-" + (sessionCounter - 1)
                ).dataset.id = sessionCounter;
                newSession.querySelector(
                    "#buttonDeleteSession-" + (sessionCounter - 1)
                ).dataset.id = sessionCounter;
                newSession.querySelector;

                // Cambiar los IDs de los elementos dentro del nuevo div
                newSession.querySelectorAll("[id]").forEach((element) => {
                    element.id = `${
                        element.id.split("-")[0]
                    }-${sessionCounter}`;
                });

                console.log(sessionCounter - 1 + " " + sessionCounter);

                const headerNewSession =
                    newSession.querySelector(".header-sesion");
                console.log(headerNewSession);
                headerNewSession
                    .querySelector("#buttonAddSession-" + sessionCounter)
                    .remove();
                headerNewSession.appendChild(buttonAddSession);
                headerNewSession
                    .querySelector("#labelAddSession-" + sessionCounter)
                    .remove();
                headerNewSession.appendChild(labelAddSession);

                // Insertar el nuevo div en el DOM
                originalSession.parentNode.appendChild(newSession);

                //----------------------------------------------
                console.log(data);
                const previews = document.querySelectorAll(".preview");
                previews.forEach((preview) => {
                    if (preview.dataset.id == sessionCounter) {
                        preview.style.backgroundColor = data.color;
                        preview.children[0].style.color = data.colorTexto;
                        preview.children[0].style.fontFamily = data.letra;
                        preview.children[0].style.fontSize = data.tamanyo + "%";
                    }
                });
                const formSesion = document.querySelectorAll(".form-sesion");
                formSesion.forEach((form) => {
                    if (form.dataset.id == sessionCounter) {
                        form.color.value = data.color;
                        form.colorTexto.value = data.colorTexto;
                        form.tamanyo.value = data.tamanyo;

                        const selectElement = form.querySelector(
                            'select[name="letra"]'
                        );
                        if (selectElement) {
                            const options = selectElement.options;
                            for (let i = 0; i < options.length; i++) {
                                if (options[i].value === data.letra) {
                                    options[i].selected = true;
                                    break;
                                }
                            }
                        }
                    }
                });

                const sessions = document.querySelectorAll(".sesion");
                sessions.forEach((session) => {
                    if (session.dataset.id == sessionCounter) {
                        session
                            .querySelector(".header-sesion")
                            .querySelector(
                                "#buttonDeleteSession-" + session.dataset.id
                            )
                            .classList.remove("hidden");
                        session
                            .querySelector(".header-sesion")
                            .querySelector(
                                "#buttonDeleteSession-" + session.dataset.id
                            )
                            .classList.add("flex");
                    } else {
                        session
                            .querySelector(".header-sesion")
                            .querySelector(
                                "#buttonDeleteSession-" + session.dataset.id
                            )
                            .classList.remove("flex");
                        session
                            .querySelector(".header-sesion")
                            .querySelector(
                                "#buttonDeleteSession-" + session.dataset.id
                            )
                            .classList.add("hidden");
                    }
                });
                document.getElementById(
                    "exportBtn-" + sessionCounter
                ).dataset.id = sessionCounter;

                buttonDeleteSessionFunc();
                colorInputFunc();
                colorTextoInputFunc();
                letraInputFunc();
                tamanyoInputFunc();
                formSectionClickFunc();
                ExportJSON();

                fetch("/add/" + sessionCounter, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                    body: JSON.stringify({
                        id: sessionCounter,
                        content: data,
                    }),
                })
                    .then((data) => {
                        if (data.success) {
                            console.log("Sesión agregada exitosamente");
                            // Aquí puedes agregar cualquier lógica adicional después de agregar la sesión
                        }
                    })
                    .catch((error) => {
                        console.error("Error al agregar la sesión:", error);
                    });
            } catch (error) {
                console.error("Error al importar el archivo JSON:", error);
            }
        };
        reader.readAsText(file);
    }
});

createButtonZero.addEventListener("click", () => {
    // Clonar el div de la sesión
    const originalSession = document.getElementById("sesion-" + sessionCounter);
    sessionCounter++;
    const newSession = originalSession.cloneNode(true);

    // Cambiar los IDs y data-ids en el nuevo div
    newSession.id = `sesion-${sessionCounter}`;
    newSession.dataset.id = sessionCounter;
    newSession.querySelector("#formSesion-" + (sessionCounter - 1)).dataset.id =
        sessionCounter;
    newSession.querySelector("#preview-" + (sessionCounter - 1)).dataset.id =
        sessionCounter;
    newSession.querySelector(
        "#buttonDeleteSession-" + (sessionCounter - 1)
    ).dataset.id = sessionCounter;
    newSession.querySelector;

    // Cambiar los IDs de los elementos dentro del nuevo div
    newSession.querySelectorAll("[id]").forEach((element) => {
        element.id = `${element.id.split("-")[0]}-${sessionCounter}`;
    });

    console.log(sessionCounter - 1 + " " + sessionCounter);

    const headerNewSession = newSession.querySelector(".header-sesion");
    console.log(headerNewSession);
    headerNewSession
        .querySelector("#buttonAddSession-" + sessionCounter)
        .remove();
    headerNewSession.appendChild(buttonAddSession);
    headerNewSession
        .querySelector("#labelAddSession-" + sessionCounter)
        .remove();
    headerNewSession.appendChild(labelAddSession);

    // Insertar el nuevo div en el DOM
    originalSession.parentNode.appendChild(newSession);
    resetConfig(sessionCounter);

    const sessions = document.querySelectorAll(".sesion");
    sessions.forEach((session) => {
        if (session.dataset.id == sessionCounter) {
            session
                .querySelector(".header-sesion")
                .querySelector("#buttonDeleteSession-" + session.dataset.id)
                .classList.remove("hidden");
            session
                .querySelector(".header-sesion")
                .querySelector("#buttonDeleteSession-" + session.dataset.id)
                .classList.add("flex");
        } else {
            session
                .querySelector(".header-sesion")
                .querySelector("#buttonDeleteSession-" + session.dataset.id)
                .classList.remove("flex");
            session
                .querySelector(".header-sesion")
                .querySelector("#buttonDeleteSession-" + session.dataset.id)
                .classList.add("hidden");
        }
    });
    document.getElementById("exportBtn-" + sessionCounter).dataset.id =
        sessionCounter;

    buttonDeleteSessionFunc();
    colorInputFunc();
    colorTextoInputFunc();
    letraInputFunc();
    tamanyoInputFunc();
    formSectionClickFunc();
    ExportJSON();

    fetch("/add/" + sessionCounter, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({
            id: sessionCounter,
            content: {
                color: "#000000",
                colorTexto: "#ffffff",
                letra: "Arial",
                tamanyo: "100",
            },
        }),
    })
        .then((data) => {
            if (data.success) {
                console.log("Sesión agregada exitosamente");
                // Aquí puedes agregar cualquier lógica adicional después de agregar la sesión
            }
        })
        .catch((error) => {
            console.error("Error al agregar la sesión:", error);
        });
});

const formSectionClickFunc = () => {
    const formSections = document.querySelectorAll(".form-sesion");

    const obtainfinalData = () => {
        let finalData = [];
        formSections.forEach((form) => {
            const dataId = form.dataset.id;
            finalData.push({
                id: dataId,
                content: {
                    color: form.color.value,
                    colorTexto: form.colorTexto.value,
                    letra: form.letra.value,
                    tamanyo: form.tamanyo.value,
                },
            });
        });
        return finalData;
    };

    formSections.forEach((formSesion) => {
        formSesion.addEventListener("submit", (e) => {
            e.preventDefault();
            const dataId = formSesion.dataset.id;
            let finalData = obtainfinalData();
            fetch("/show/" + dataId, {
                method: "POST",
                body: JSON.stringify(finalData),
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            }).then((response) => {
                window.location.href = "/show/" + dataId;
                console.log(response.json);
            });
        });
    });
};
formSectionClickFunc();
