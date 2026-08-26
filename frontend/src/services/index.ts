/**
 * Barrel re-export for all feature API modules.
 *
 * Usage:
 *   import { getCities } from "@/src/services";
 *   import { createEvent } from "@/src/services";
 *
 * Or import from the specific module directly:
 *   import { getCities } from "@/src/services/home";
 */

// ── Client & types ───────────────────────────────────────────────────
export { api, API_BASE_URL, checkHealth } from "./client";
export type {
  City,
  Area,
  State,
  Country,
  User,
  AuthResponse,
  LoginPayload,
  RegisterPayload,
  HealthResponse,
  PaginatedResponse,
} from "./types";

// ── Auth ─────────────────────────────────────────────────────────────
export {
  register,
  login,
  logout,
  forgotPassword,
  resetPassword,
  GOOGLE_AUTH_URL,
  FACEBOOK_AUTH_URL,
  setAuthToken,
  clearAuthToken,
  isAuthenticated,
} from "./auth";

// ── Home ─────────────────────────────────────────────────────────────
export {
  getCities,
  searchCities,
  getCitiesByState,
  getAreasByCity,
  getCategories,
  getParentCategories,
  getCategoriesByCity,
  getSubcategories,
} from "./home";

// ── Events ───────────────────────────────────────────────────────────
export {
  getAllEvents,
  getEventsByCity,
  getEventsByCategory,
  getEventsByCategoryInCity,
  loadEventsByScrolling,
  createEvent,
  updateEvent,
  deleteEvent,
  markGoing,
  markNotGoing,
  markInterested,
  markNotInterested,
  getEventCategories,
  getEventParentCategories,
} from "./events";

// ── Marketplace ──────────────────────────────────────────────────────
export {
  getAllProducts,
  getProductsByCity,
  loadProductsByScrolling,
  createProduct,
  updateProduct,
  deleteProduct,
  saveProduct,
  unsaveProduct,
  getSavedProducts,
  getProductCategories,
  getProductParentCategories,
  getBrands,
  submitEnquiry,
} from "./marketplace";

// ── Pages ────────────────────────────────────────────────────────────
export {
  getAllPages,
  getPagesByCity,
  loadPagesByScrolling,
  getPageSuggestions,
  createPage,
  updatePage,
  deletePage,
  claimListing,
  loadPageVideos,
} from "./pages";

// ── Groups ───────────────────────────────────────────────────────────
export {
  getAllGroups,
  getGroupsByCity,
  loadGroupsByScrolling,
  createGroup,
  updateGroup,
  joinGroup,
  requestJoinGroup,
  sendGroupInvites,
  reportGroup,
  getGroupCategories,
  getGroupParentCategories,
} from "./groups";

// ── Blog ─────────────────────────────────────────────────────────────
export {
  getAllBlogs,
  getBlogsByCity,
  loadBlogsByScrolling,
  createBlog,
  updateBlog,
  deleteBlog,
  searchBlogs,
  getBlogCategories,
  getBlogParentCategories,
} from "./blog";

// ── Chat ─────────────────────────────────────────────────────────────
export {
  sendMessage,
  loadChatData,
  markMessagesRead,
  deleteMessage,
  searchChatProfiles,
  reactToMessage,
  chatWithSupport,
  sendPageMessage,
  fetchPageChatMessages,
  sendMarketplaceMessage,
  fetchMarketplaceMessages,
} from "./chat";

// ── Posts ────────────────────────────────────────────────────────────
export {
  createPost,
  updatePost,
  loadPostsByScrolling,
  getSinglePost,
  deletePost,
  addComment,
  loadComments,
  reactToPost,
  shareToTimeline,
  shareToGroup,
  createStory,
  getStories,
  getStoryDetails,
} from "./posts";

// ── Videos ───────────────────────────────────────────────────────────
export {
  getAllVideos,
  getAllShorts,
  loadVideosByScrolling,
  loadShortsByScrolling,
  createVideo,
  deleteVideo,
  saveShort,
  unsaveShort,
  getSavedVideos,
} from "./videos";

// ── User ─────────────────────────────────────────────────────────────
export {
  getProfile,
  getViewProfile,
  updateProfile,
  uploadPhoto,
  updateAbout,
  getFriends,
  addFriend,
  unfriend,
  followUser,
  unfollowUser,
  changePassword,
  getDashboard,
  getUserAds,
  createAd,
  getWalletBalance,
  addWalletMoney,
  getSubscriptionPlans,
  subscribe,
  reviewPage,
  reviewProduct,
  getLeads,
  buyLead,
  createTicket,
  getTickets,
} from "./user";

// ── Search ───────────────────────────────────────────────────────────
export {
  searchGlobally,
  searchPeople,
  searchPosts,
  searchVideos,
  searchProducts,
  searchPages,
  searchGroups,
  searchEvents,
} from "./search";

// ── Notifications ────────────────────────────────────────────────────
export {
  getNotifications,
  markAsRead,
  acceptFriendRequest,
  declineFriendRequest,
  acceptGroupRequest,
  declineGroupRequest,
  acceptEventRequest,
  declineEventRequest,
} from "./notifications";
