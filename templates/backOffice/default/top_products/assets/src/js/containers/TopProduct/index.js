import React, { useState, useEffect } from "react";
import {SortableElement} from "react-sortable-hoc";

import SearchForm from '../SearchForm';
import Product from '../../components/Product';

export default SortableElement(({topProduct, deleteProduct, updateProduct}) => {
    const [edit, setEdit] = useState(false);

    const {reference, title, visible} = topProduct.product;

    const handleSubmit = (product) => {
        updateProduct(topProduct.id, product);
        setEdit(false);
    };

    const renderEditButton = () => {
        return (
            <a className='btn btn-info' onClick={() => setEdit(!edit)}>
                <i className="glyphicon glyphicon-edit"></i>
            </a>
        )
    };

    const renderDeleteButton = () => {
        return (
            <a className="btn btn-danger" onClick={() => deleteProduct(topProduct.id)} >
                <i className="glyphicon glyphicon-trash"></i>
            </a>
        )
    };

    return (
        <li className="TopProducts-product-list-item">
            {edit ? null : <Product reference={reference} title={title} visible={visible} />}
            {edit ? <SearchForm submitProduct={handleSubmit} cancelSearch={() => setEdit(false)} forceCancelButton={true}/> : renderEditButton()}
            {edit ? null : renderDeleteButton()}
        </li>
    )
});