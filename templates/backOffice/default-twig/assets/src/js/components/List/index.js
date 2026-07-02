import React from "react";

import { SortableContainer } from "react-sortable-hoc";

import SearchForm from "../../containers/SearchForm";
import TopProduct from "../../containers/TopProduct";

export default SortableContainer(({items, addProduct, deleteProduct, updateProduct}) => {

    return (
        <ul className="TopProducts-product-list">
            {items.map((item, index) => {
                    return <TopProduct key={`item-${index}`} index={index} topProduct={item} deleteProduct={deleteProduct} updateProduct={updateProduct} />
                }
            )}
            <li className="TopProducts-product-list-item">
                <SearchForm submitProduct={addProduct} cancelSearch={() => {}} forceCancelButton={false}/>
            </li>
        </ul>
    );
});