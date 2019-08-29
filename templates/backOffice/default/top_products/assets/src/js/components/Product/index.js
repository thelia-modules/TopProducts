import React from "react";

export default ({title, reference, visible}) => {

    return (
        <div>
            <strong>{reference}</strong>
            <br/>
            {title}  {visible ? <i className="glyphicon glyphicon-ok-sign text-success"></i> : <i className="glyphicon glyphicon-remove-sign text-danger"></i>}
        </div>
    )
}