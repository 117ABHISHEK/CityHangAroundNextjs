import { api } from "./client";

// ── Product type ─────────────────────────────────────────────────────

export interface Product {
  id: number;
  product_name: string;
  product_slug: string;
  description: string;
  price: number;
  condition: "new" | "used";
  images: string[];
  city_id: number;
  area_id: number | null;
  category_id: number;
  brand_id: number | null;
  view: number;
  created_at: string;
}

export interface ProductCategory {
  id: number;
  category_name: string;
  parent?: string;
}

export interface Brand {
  id: number;
  name: string;
}

// ── Public (no auth) ─────────────────────────────────────────────────

/**
 * Fetch all products.
 *
 * Backend: GET /api/public/products
 */
export function getAllProducts(): Promise<Product[]> {
  return api.get<Product[]>("/api/public/products");
}

/**
 * Fetch products for a specific city.
 *
 * Backend: GET /api/public/products/{city_slug}
 */
export function getProductsByCity(citySlug: string): Promise<Product[]> {
  return api.get<Product[]>(`/api/public/products/${citySlug}`);
}

/**
 * Infinite-scroll loader for products.
 *
 * Backend: GET /api/public/products/scroll?offset=0&limit=20
 */
export function loadProductsByScrolling(
  offset: number,
  limit: number,
): Promise<Product[]> {
  return api.get<Product[]>("/api/public/products/scroll", {
    params: { offset, limit },
  });
}

// ── Auth required ────────────────────────────────────────────────────

/**
 * Backend: POST /product/store (auth required)
 */
export function createProduct(payload: FormData): Promise<{ id: number }> {
  return api.post<{ id: number }>("/product/store", payload);
}

/**
 * Backend: POST /update/product/{id} (auth required)
 */
export function updateProduct(id: number, payload: FormData): Promise<void> {
  return api.post<void>(`/update/product/${id}`, payload);
}

/**
 * Backend: GET /product/delete?id={id} (auth required)
 */
export function deleteProduct(id: number): Promise<void> {
  return api.get<void>("/product/delete", { params: { id } });
}

/**
 * Backend: GET /save/product/{id} (auth required)
 */
export function saveProduct(id: number): Promise<void> {
  return api.get<void>(`/save/product/${id}`);
}

/**
 * Backend: GET /unsave/product/{id} (auth required)
 */
export function unsaveProduct(id: number): Promise<void> {
  return api.get<void>(`/unsave/product/${id}`);
}

/**
 * Backend: GET /product/saved/ (auth required)
 */
export function getSavedProducts(): Promise<Product[]> {
  return api.get<Product[]>("/product/saved/");
}

// ── Product Categories ───────────────────────────────────────────────

/**
 * Backend: GET /api/public/product-categories
 */
export function getProductCategories(): Promise<ProductCategory[]> {
  return api.get<ProductCategory[]>("/api/public/product-categories");
}

/**
 * Backend: GET /api/public/product-categories/parent
 */
export function getProductParentCategories(): Promise<ProductCategory[]> {
  return api.get<ProductCategory[]>("/api/public/product-categories/parent");
}

/**
 * Backend: GET /api/public/brands
 */
export function getBrands(): Promise<Brand[]> {
  return api.get<Brand[]>("/api/public/brands");
}

// ── Enquiry (public) ─────────────────────────────────────────────────

/**
 * Backend: POST /enquiry
 */
export function submitEnquiry(payload: {
  product_id: number;
  name: string;
  email: string;
  phone: string;
  message: string;
}): Promise<void> {
  return api.post<void>("/enquiry", payload);
}
