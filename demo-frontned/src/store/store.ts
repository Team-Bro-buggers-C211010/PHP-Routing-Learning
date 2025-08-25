import { create } from "zustand";

interface SearchState {
  query: string;
  setQuery: (value: string) => void;
}


const useSearchStore = create<SearchState>((set) => ({
  query: "",
  setQuery: (value) => set({ query: value }),
}));

export default useSearchStore;