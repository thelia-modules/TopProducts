import React, { useState, useEffect } from "react";

import {searchProducts} from '../../utils/api';
import Product from '../../components/Product';

export default ({submitProduct, cancelSearch, forceCancelButton}) => {
    const [timeoutId, setTimeoutId] = useState(null);
    const [search, setSearch] = useState("");
    const [products, setProducts] = useState([]);
    const [selectedProduct, setSelectedProduct] = useState(null);

    const resetForm = () => {
        setProducts([]);
        setSearch("");
        setSelectedProduct(null);
    }

    const handleKeyUp = (e) => {
        if (null !== timeoutId) {
            clearTimeout(timeoutId);
        }

        setProducts([]);

        if (search.length > 3) {
            const timeoutId = setTimeout(() => {
                handleSearch(search);
            }, 300);
            setTimeoutId(timeoutId);
        }
    };

    const handleSearch = (search) => {
        searchProducts(search).then((response) => {
                const { products } = response.data;
                setProducts(products);
            })
    };

    const handleSelect = (product) => {
        setSelectedProduct(product);
    };

    const handleCancel = () => {
        resetForm();
        cancelSearch();
    }

    const renderSubmitButton = () => {
        return(
            <a className='btn btn-block btn-success' onClick={() => {
                submitProduct(selectedProduct);
                resetForm();
            }}>
                <i className="glyphicon glyphicon-ok"></i>
            </a>
        )
    };

    const renderProductList = () => {
        return (
            <div className="TopProducts-product-select">
                {products.map((product, index) => {
                    const {reference, title, visible} = product;

                    return (
                        <div key={index} onClick={() => {handleSelect(product);}}>
                            <Product reference={reference} title={title} visible={visible} />
                        </div>
                    )
                })}
            </div>
        )
    };

    const renderCancelButton = () => {
        if (search.length < 1 && forceCancelButton === false) {
            return null;
        }

        return (
            <span className="input-group-btn">
                <button className="btn btn-warning" type="button" onClick={handleCancel}><i className="glyphicon glyphicon-remove"></i></button>
            </span>
        )
    }

    return (
        <>
            <div className="input-group">
                <span className="input-group-addon"><i className="glyphicon glyphicon-search"></i></span>
                <input className='form-control' type="text" onChange={(e) => setSearch(e.target.value)} onKeyUp={handleKeyUp} value={search} />
                {renderCancelButton()}
            </div>

            {selectedProduct ? <><div>{selectedProduct.title}</div>{renderSubmitButton()}</> : renderProductList()}
        </>
    )
}