import { useState, useEffect } from "react";
import axios from "axios";
import type { Users } from "../Types/types";

const UsersList = () => {
  const [users, setUsers] = useState<Users[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string|null>(null);

  useEffect(() => {
    const callUsersApi = async () => {
      try {
        const response = await axios.get("http://localhost:8001/users");
        setUsers(response.data);
      } catch (err) {
        if (err instanceof Error) {
          setError(err.message);
        }
      } finally {
        setLoading(false);
      }
    };
    callUsersApi();
  }, []);

  if (loading) {
    return <div>Loading users...</div>;
  }

  if (error) {
    return <div>Error: {error}</div>;
  }

  return (
    <div>
      <h1 className="text-2xl font-bold">Users List</h1>
      <ul className="border-2 border-amber-300 w-full">
        {users.map((user, idx) => (
          <>
            <li className="w-full text-lg font semi bold flex justtify-between gap-4 border-b-2 border-amber-200" key={user.id}>
              <div>User: {idx+1}</div>
              <div>
                Username: {user.name}
              </div>
              <div>Email: {user.email}</div>
            </li>
          </>
        ))}
      </ul>
    </div>
  );
};

export default UsersList;
