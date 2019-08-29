import React, { useState, useEffect } from "react";

export default ({addSelection}) => {
    const [selectionCode, setSelectionCode] = useState("");

    return (
        <div className="col-md-6 col-md-offset-3 input-group">
            <input className='form-control' placeholder="Créer une nouvelle sélection" type="text" onChange={(e) => setSelectionCode(e.target.value)} value={selectionCode} />
            <span className="input-group-btn">
                <button className="btn btn-success" type="button" onClick={() => {
                    setSelectionCode("");
                    addSelection(selectionCode);
                }}>
                    <i className="glyphicon glyphicon-plus"></i>
                </button>
            </span>
        </div>
    )
}