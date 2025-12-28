defmodule PhoenixApi.Accounts.User do
  use Ecto.Schema
  import Ecto.Changeset

  @genders [:male, :female]

  schema "users" do
    # field(:id, :id)
    field(:first_name, :string)
    field(:last_name, :string)
    field(:birthdate, :date)
    field(:gender, Ecto.Enum, values: @genders)

    timestamps(type: :utc_datetime)
  end

  @doc false
  def changeset(user, attrs) do
    user
    |> cast(attrs, [:first_name, :last_name, :birthdate, :gender])
    |> validate_required([:first_name, :last_name, :birthdate, :gender])
    |> validate_inclusion(:gender, @genders)
  end
end
