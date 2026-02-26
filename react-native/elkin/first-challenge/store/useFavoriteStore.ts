// store/useFavoriteStore.ts
import { create } from 'zustand';

// Define the shape of your item based on your HomeScreen data
interface Product {
    id: number;
    title: string;
    description: string;
    price: number;
    category?: string;
    image?: string;
}

// Define the state and actions for the store
interface FavoritesState {
    favorites: Product[];
    itemToRemove: Product | null;
    isModalVisible: boolean;
    toggleFavorite: (product: Product) => void;
    isFavorite: (id: number) => boolean;
    promptRemoveFavorite: (product: Product) => void;
    confirmRemoveFavorite: () => void;
    cancelRemoveFavorite: () => void;
}

export const useFavoritesStore = create<FavoritesState>((set, get) => ({
    favorites: [],
    itemToRemove: null,
    isModalVisible: false,

    // Action to add or remove a favorite directly
    toggleFavorite: (product) => set((state) => {
        const isAlreadyFavorite = state.favorites.some((item) => item.id === product.id);

        if (isAlreadyFavorite) {
            // If already favorite, prompt removal instead of instant removal
            return { itemToRemove: product, isModalVisible: true };
        } else {
            // Add if it doesn't exist
            return { favorites: [...state.favorites, product] };
        }
    }),

    // Helper to check if an item is favorited
    isFavorite: (id) => get().favorites.some((item) => item.id === id),

    promptRemoveFavorite: (product) => set({ itemToRemove: product, isModalVisible: true }),

    confirmRemoveFavorite: () => set((state) => {
        if (!state.itemToRemove) return state;
        return {
            favorites: state.favorites.filter((item) => item.id !== state.itemToRemove!.id),
            itemToRemove: null,
            isModalVisible: false
        };
    }),

    cancelRemoveFavorite: () => set({ itemToRemove: null, isModalVisible: false }),
}));