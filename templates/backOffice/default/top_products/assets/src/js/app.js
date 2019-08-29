import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import arrayMove from 'array-move';

import { getTopProducts, addTopProduct, removeTopProduct, updateTopProduct, updateTopProductPosition } from './utils/api'
import List from './components/List'
import SelectionForm from './components/SelectionForm'

class App extends Component {
    state = {
        topProductSelections: []
    };

    componentDidMount() {
        this.fetchProducts();
    }

    fetchProducts = () => {
        getTopProducts().then(({data}) => {
            this.setState({topProductSelections: data.topProductSelections})
        })
    };

    handleAddSelection = (selectionCode) => {
        const alreadyExist = this.state.topProductSelections.findIndex((topProductSelection) => {
            return topProductSelection.code === selectionCode;
        })

        if (alreadyExist === -1) {
            this.setState(({topProductSelections}) => ({
                topProductSelections: [...topProductSelections, {code:selectionCode, topProducts:[]}]
            }))
        }
    }

    handleAddProduct = (product, selectionCode) => {
        addTopProduct(product.id, selectionCode).then(({data}) => {
            this.setState(({topProductSelections}) => ({
                topProductSelections: topProductSelections.map((topProductSelection) => {
                    if (topProductSelection.code !== selectionCode) {
                        return topProductSelection;
                    }

                    const {id, position} = data;
                    const topProducts = [...topProductSelection.topProducts, {id, position, product}];

                    return  {...topProductSelection, topProducts}
                })
            }));
        })
    };

    handleDeleteProduct = (topProductId, selectionCode) => {
        removeTopProduct(topProductId).then(({data}) => {
            this.setState(({topProductSelections}) => ({
                topProductSelections: topProductSelections.map((topProductSelection) => {
                    if (topProductSelection.code !== selectionCode) {
                        return topProductSelection;
                    }

                    const topProducts = topProductSelection.topProducts.filter((topProduct) => topProduct.id !== topProductId);

                    return  {...topProductSelection, topProducts}
                })
            }));
        })
    };

    handleUpdateProduct = (topProductId, newProduct, selectionCode) => {
        updateTopProduct(topProductId, newProduct.id).then(({data}) => {
            this.setState(({topProductSelections}) => ({
                topProductSelections: topProductSelections.map((topProductSelection) => {
                    if (topProductSelection.code !== selectionCode) {
                        return topProductSelection;
                    }

                    const topProducts = topProductSelection.topProducts.map((topProduct) => {
                        if (topProduct.id === topProductId) {
                            topProduct.product = newProduct;
                        }

                        return topProduct;
                    });

                    return  {...topProductSelection, topProducts}
                })
            }));
        })
    };

    onSortEnd = (oldIndex, newIndex, selectionIndex) => {
        updateTopProductPosition(this.state.topProductSelections[selectionIndex].topProducts[oldIndex].id, newIndex)
            .then(() => {
                this.setState(({topProductSelections}) => ({
                    topProductSelections: topProductSelections.map((topProductSelection, index) => {
                        if (index !== selectionIndex) {
                            return topProductSelection;
                        }

                        const topProducts = arrayMove(topProductSelection.topProducts, oldIndex, newIndex)
                        return  {...topProductSelection, topProducts}
                    })
                }));
            })
    };

    render() {
        const { topProductSelections } = this.state;

        return (
            <>
                {topProductSelections.map((topProductSelection, index) => {
                    return this.renderTopProductSelection(topProductSelection, index)
                })}
                <SelectionForm addSelection={this.handleAddSelection}/>
            </>
        )
    }

    renderTopProductSelection({code, topProducts}, index) {
        return (
            <div key={index}>
                <h3>Selection : {code}</h3>
                <ul key={index} className="TopProducts-product-list">
                    <List
                        items={topProducts}
                        onSortEnd={({oldIndex, newIndex}) => this.onSortEnd(oldIndex, newIndex, index)}
                        distance={1}
                        axis="xy"
                        addProduct={(product) => this.handleAddProduct(product, code)}
                        deleteProduct={(topProductId) => this.handleDeleteProduct(topProductId, code)}
                        updateProduct={(topProductId, newProduct) => this.handleUpdateProduct(topProductId, newProduct, code)}
                    />
                </ul>
            </div>
        )
    }

}

ReactDOM.render(
<App/>,
    document.getElementById('top_products_container')
);