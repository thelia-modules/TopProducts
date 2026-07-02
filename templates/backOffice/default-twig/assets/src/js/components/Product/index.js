import React from "react";

export default ({title, reference, visible}) => {

    return (
        <div>
            <strong>{reference}</strong>
            <br/>
            {title}  {visible ? <i className="bi bi-check text-success"></i> : <i className="bi bi-x-lg text-danger"></i>}
        </div>
    )
}
