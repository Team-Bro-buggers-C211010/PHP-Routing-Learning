import { useQuery } from "@tanstack/react-query";
import axios from "axios";
import type { Users } from "../Types/types";
import { useDebounce } from "use-debounce";
import useSearchStore from "../store/store";

const UsersList = () => {
  const { query } = useSearchStore();

  const [debouncedQuery] = useDebounce(query, 500);

  const fetchUsers = async (): Promise<Users[]> => {
    const response = await axios.get(
      `http://localhost:8080/users?name=${debouncedQuery}`,
      { withCredentials: true }
    );
    return response.data;
  };

  const {
    data: users,
    error,
    isLoading,
  } = useQuery<Users[]>({
    queryKey: ["users", debouncedQuery], // include query in cache key
    queryFn: fetchUsers,
  });

  if (isLoading) {
    return <div>Loading users...</div>;
  }

  if (error) {
    return <div>Error: {(error as Error).message}</div>;
  }

  return (
    <div>
      <h1 className="text-2xl font-bold">Users List</h1>
      <ul className="border-2 border-amber-300 w-full">
        {users?.map((user, idx) => (
          <li
            key={user.id}
            className="w-full text-lg font-semibold flex justify-between gap-4 border-b-2 border-amber-200"
          >
            <div>User: {idx + 1}</div>
            <div>Username: {user.name}</div>
            <div>Email: {user.email}</div>
          </li>
        ))}
      </ul>
    </div>
  );
};

export default UsersList;
