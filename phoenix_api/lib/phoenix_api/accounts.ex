defmodule PhoenixApi.Accounts do
  @moduledoc """
  The Accounts context.
  """

  import Ecto.Query, warn: false
  alias PhoenixApi.Repo

  alias PhoenixApi.Accounts.User

  @doc """
  Returns the list of users.

  ## Examples

      iex> list_users()
      [%User{}, ...]

  """
  def list_users(params) do
    sortable_fields = ["first_name", "last_name", "gender", "birthdate"]
    sort_direction = if params["sort_order"] == "desc", do: :desc, else: :asc
    sort_by = if params["sort_by"] in sortable_fields, do: params["sort_by"], else: :id

    User
    |> filter_by("first_name", params["first_name"])
    |> filter_by("last_name", params["last_name"])
    |> filter_by("gender", params["gender"])
    |> filter_birthdate_range(:after, params["born_after"])
    |> filter_birthdate_range(:before, params["born_before"])
    |> order_by([u], [{^sort_direction, field(u, ^sort_by)}])
    |> Repo.all()
  end

  defp filter_by(query, _field_name, nil), do: query

  defp filter_by(query, field_name, value) do
    where(query, [u], field(u, ^field_name) == ^value)
  end

  defp filter_birthdate_range(query, _type, nil), do: query

  defp filter_birthdate_range(query, :after, value) do
    where(query, [u], u.birthdate >= ^value)
  end

  defp filter_birthdate_range(query, :before, value) do
    where(query, [u], u.birthdate <= ^value)
  end

  @doc """
  Gets a single user.

  Raises `Ecto.NoResultsError` if the User does not exist.

  ## Examples

      iex> get_user!(123)
      %User{}

      iex> get_user!(456)
      ** (Ecto.NoResultsError)

  """
  def get_user!(id), do: Repo.get!(User, id)

  @doc """
  Creates a user.

  ## Examples

      iex> create_user(%{field: value})
      {:ok, %User{}}

      iex> create_user(%{field: bad_value})
      {:error, %Ecto.Changeset{}}

  """
  def create_user(attrs) do
    %User{}
    |> User.changeset(attrs)
    |> Repo.insert()
  end

  @doc """
  Updates a user.

  ## Examples

      iex> update_user(user, %{field: new_value})
      {:ok, %User{}}

      iex> update_user(user, %{field: bad_value})
      {:error, %Ecto.Changeset{}}

  """
  def update_user(%User{} = user, attrs) do
    user
    |> User.changeset(attrs)
    |> Repo.update()
  end

  @doc """
  Deletes a user.

  ## Examples

      iex> delete_user(user)
      {:ok, %User{}}

      iex> delete_user(user)
      {:error, %Ecto.Changeset{}}

  """
  def delete_user(%User{} = user) do
    Repo.delete(user)
  end

  @doc """
  Returns an `%Ecto.Changeset{}` for tracking user changes.

  ## Examples

      iex> change_user(user)
      %Ecto.Changeset{data: %User{}}

  """
  def change_user(%User{} = user, attrs \\ %{}) do
    User.changeset(user, attrs)
  end

  @doc """
  Imports a 100 users.

  """
  def import do
    name_count = 100
    name_data = load_name_data(name_count)

    user_count = 100

    users =
      Enum.map(1..user_count, fn _ ->
        gender = Enum.random([:male, :female])
        first_names = name_data |> Map.get(:"#{gender}_names")
        last_names = name_data |> Map.get(:"#{gender}_surnames")
        first_name = Enum.random(first_names)
        last_name = Enum.random(last_names)

        %User{
          gender: gender,
          first_name: first_name,
          last_name: last_name,
          birthdate: random_birthdate()
        }
      end)

    Repo.transaction(fn ->
      Enum.map(users, fn user ->
        Repo.insert!(user)
      end)
    end)
  end

  @doc """
  Returns random birthdate between 01-01-1970 – 31-12-2024
  """
  defp random_birthdate do
    Date.range(Date.new!(1970, 1, 1), Date.new!(2024, 12, 31))
    |> Enum.random()
  end

  @doc """
  Loads name data from csv files.
  """
  defp load_name_data(name_count) do
    %{
      female_names: load_csv("name_female.csv", name_count),
      male_names: load_csv("name_male.csv", name_count),
      female_surnames: load_csv("surname_female.csv", name_count),
      male_surnames: load_csv("surname_male.csv", name_count)
    }
  end

  defp load_csv(filename, name_count) do
    path = Application.app_dir(:phoenix_api, "priv/#{filename}")
    header_lines = 1

    File.stream!(path)
    |> Stream.drop(header_lines)
    |> Stream.take(name_count)
    |> Stream.map(fn line ->
      line
      |> String.split(",")
      |> hd()
    end)
    |> Enum.to_list()
  end
end
