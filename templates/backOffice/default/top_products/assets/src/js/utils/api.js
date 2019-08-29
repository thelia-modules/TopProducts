import axios from "axios/index";

export const searchProducts = (search) => axios.get(`/admin/top_products/search/${topProductElementKey}/${topProductElementId}?q=${search}`);

export const getTopProducts = () => axios.get(`/admin/top_products/get/${topProductElementKey}/${topProductElementId}`);

export const addTopProduct = (productId, selectionCode) => axios.post(`/admin/top_products/add/${topProductElementKey}/${topProductElementId}/${selectionCode}`, {productId});

export const removeTopProduct = (topProductId) => axios.post(`/admin/top_products/remove/${topProductId}`);

export const updateTopProduct = (topProductId, newProductId) => axios.post(`/admin/top_products/update/${topProductId}`, {newProductId});

export const updateTopProductPosition = (topProductId, newPosition) => axios.post(`/admin/top_products/position/${topProductId}`, {newPosition});